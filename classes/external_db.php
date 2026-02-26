<?php
namespace block_educolab;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper class for connecting to the external EduColab database.
 *
 * @package   block_educolab
 */
class external_db {

    /** @var string Database host */
    private const HOST = '172.18.12.13';

    /** @var int Database port */
    private const PORT = 3306;

    /** @var string Database name */
    private const DBNAME = 'educolab';

    /** @var string Database user */
    private const USER = 'educolab_user';

    /** @var string Database password */
    private const PASS = 'P1b1c-f4pdf';

    /**
     * Get a PDO connection to the external database.
     *
     * @return \PDO
     * @throws \dml_exception
     */
    public static function get_connection(): \PDO {
        static $pdo = null;

        if ($pdo === null) {
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', self::HOST, self::PORT, self::DBNAME);
            try {
                $pdo = new \PDO($dsn, self::USER, self::PASS, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                ]);
            } catch (\PDOException $e) {
                throw new \dml_exception('dbconnectfailed', null, $e->getMessage());
            }
        }

        return $pdo;
    }

    /**
     * Get forum dates from the external database.
     *
     * @param int $forumid The Moodle course module ID used as ForumID
     * @return object|false Object with start_date and end_date properties, or false if not found
     */
    public static function get_forum_dates(int $forumid) {
        $pdo = self::get_connection();
        $stmt = $pdo->prepare('SELECT DataInicio, DataFinal FROM foruns WHERE ForumID = :forumid LIMIT 1');
        $stmt->execute(['forumid' => $forumid]);
        $row = $stmt->fetch();

        if (!$row) {
            return false;
        }

        $result = new \stdClass();
        $result->start_date = $row->DataInicio ? strtotime($row->DataInicio) : null;
        $result->end_date = $row->DataFinal ? strtotime($row->DataFinal) : null;

        return $result;
    }

    /**
     * Generate and save a token for a user.
     * If a token already exists for this user, it will be replaced.
     *
     * @param int $userid The Moodle user ID
     * @return string The generated token
     * @throws \PDOException
     */
    public static function generate_user_token(int $userid): string {        
        $pdo = self::get_connection();
        
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        
        try {
            // Delete any existing tokens for this user first
            $deleteStmt = $pdo->prepare('DELETE FROM tokens WHERE userid = :userid');
            $deleteStmt->execute(['userid' => $userid]);
            
            // Insert the new token
            $insertStmt = $pdo->prepare('
                INSERT INTO tokens (userid, token, is_valid)
                VALUES (:userid, :token, TRUE)
            ');
            $insertStmt->execute([
                'userid' => $userid,
                'token' => $token,
            ]);
            
            return $token;
        } catch (\PDOException $e) {
            // Log the error for debugging
            error_log('PDO Error in generate_user_token: ' . $e->getMessage() . ' Code: ' . $e->getCode());
            throw $e;
        }
    }

    /**
     * Validate and retrieve a token record.
     *
     * @param string $token The token to validate
     * @return object|false Token record if valid, false otherwise
     * @throws \PDOException
     */
    public static function validate_token(string $token) {
        $pdo = self::get_connection();
        $stmt = $pdo->prepare('SELECT id, userid, is_valid FROM tokens WHERE token = :token LIMIT 1');
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch();
        
        if (!$row || !$row->is_valid) {
            return false;
        }
        
        return $row;
    }

    /**
     * Invalidate a token.
     *
     * @param string $token The token to invalidate
     * @throws \PDOException
     */
    public static function invalidate_token(string $token): void {
        $pdo = self::get_connection();
        $stmt = $pdo->prepare('UPDATE tokens SET is_valid = FALSE WHERE token = :token');
        $stmt->execute(['token' => $token]);
    }
}
