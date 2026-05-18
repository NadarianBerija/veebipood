<?php

namespace {
    if (!class_exists('Database', false)) {
        class Database {
            public static array $queries = [];
            public static array $responses = [];
            public static int $lastInsertId = 1;
            public static bool $throwOnMatch = false;

            public function __construct() {}

            /* Resets the database state, clearing all recorded queries and responses, and resetting the last insert ID and throwOnMatch flag. */
            public static function reset(): void {
                self::$queries = [];
                self::$responses = [];
                self::$lastInsertId = 1;
                self::$throwOnMatch = false;
            }

            /* 
             * Registers a response for a specific query pattern, which can be a string or a callable matcher. The response can also be a callable that generates the response based on the query and parameters. 
             */
            public static function onQuery(string|callable $matcher, mixed $response): void {
                self::$responses[] = ['matcher' => $matcher, 'response' => $response];
            }

            /* Simulates the getOne method of a database connection, recording the query and parameters, and returning a response based on the registered matchers. */
            public function getOne(string $query, array $params = []) {
                self::$queries[] = ['type' => 'getOne', 'query' => $query, 'params' => $params];
                return $this->resolve($query, $params);
            }

            /* Simulates the getAll method of a database connection, recording the query and parameters, and returning a response based on the registered matchers. */
            public function getAll(string $query, array $params = []) {
                self::$queries[] = ['type' => 'getAll', 'query' => $query, 'params' => $params];
                return $this->resolve($query, $params);
            }

            /* Simulates the executeRun method of a database connection, recording the query and parameters, and returning a response based on the registered matchers. If no response is registered, it returns true by default. */
            public function executeRun(string $query, array $params = []) {
                self::$queries[] = ['type' => 'executeRun', 'query' => $query, 'params' => $params];
                $result = $this->resolve($query, $params);
                return $result === null ? true : $result;
            }

            /* Simulates the beginTransaction method of a database connection. */
            public function beginTransaction() {
                return true;
            }

            /* Simulates the commit method of a database connection. */
            public function commit() {
                return true;
            }

            /* Simulates the rollBack method of a database connection. */
            public function rollBack() {
                return true;
            }

            /* Returns the last inserted ID. */
            public function getLastId() {
                return self::$lastInsertId;
            }

            /* Resolves a query against the registered responses. */
            private function resolve(string $query, array $params) {
                foreach (self::$responses as $entry) {
                    $matcher = $entry['matcher'];
                    $matches = false;

                    if (is_string($matcher)) {
                        $matches = $matcher === $query;
                    } elseif (is_callable($matcher)) {
                        $matches = $matcher($query, $params);
                    }

                    if ($matches) {
                        $response = $entry['response'];
                        return is_callable($response) ? $response($query, $params) : $response;
                    }
                }

                if (self::$throwOnMatch) {
                    throw new RuntimeException('Unexpected query: ' . $query);
                }

                return null;
            }
        }
    }
}

/* 
 * PHPMailer stub to simulate email sending behavior in tests, allowing control over success and failure scenarios.
 */
namespace PHPMailer\PHPMailer {
    if (!class_exists('Exception', false)) {
        class Exception extends \Exception {}
    }

    if (!class_exists('PHPMailer', false)) {
        class PHPMailer {
            public static bool $sendSuccess = true;
            public string $Host = '';
            public bool $SMTPAuth = false;
            public string $Username = '';
            public string $Password = '';
            public string $SMTPSecure = '';
            public int $Port = 0;
            public string $CharSet = '';
            public string $Subject = '';
            public string $Body = '';
            public string $ErrorInfo = '';

            public const ENCRYPTION_STARTTLS = 'STARTTLS';

            public function __construct(bool $exceptions = false) {
                if ($exceptions) {
                    return;
                }
            }

            public function isSMTP(): void {}
            public function setFrom(string $address, string $name = ''): void {}
            public function addAddress(string $address): void {}
            public function addReplyTo(string $address, string $name = ''): void {}
            public function isHTML(bool $isHtml): void {}
            public function send(): bool {
                if (!self::$sendSuccess) {
                    $this->ErrorInfo = 'Send failure';
                    throw new Exception('Send failure');
                }
                return true;
            }
        }
    }
}
