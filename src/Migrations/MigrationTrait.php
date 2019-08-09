<?php


namespace Artica\Migrations;


use InvalidArgumentException;
use yii\db\ColumnSchemaBuilder;
use yii\helpers\Inflector;

/**
 * Trait EnumTrait
 * Add enum col generator to migration class.
 *
 * @package console\migrations
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
trait MigrationTrait
{
    public $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';

    /**
     * Generate enum col.
     *
     * @param array       $params
     * @param null|string $default
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function enum(array $params, string $default = null): string
    {
        if (!is_array($params) or count($params) == 0) {
            throw new InvalidArgumentException("Params should be array.");
        }
        $sql = "ENUM(";
        for ($i = 0; $i < count($params); $i++) {
            if ($i != 0) {
                $sql .= ", ";
            }
            $sql .= "'{$params[$i]}'";
        }
        $sql .= ")";

        if ($default) {
            $sql .= " NOT NULL DEFAULT '{$default}'";
        }

        return $sql;
    }

    /**
     * Creates a string column.
     *
     * @param int $length column size definition i.e. the maximum string length.
     *                    This parameter will be ignored if not supported by the DBMS.
     *
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function string($length = null)
    {
        if ($length === null) {
            $length = 191;
        }
        return parent::string($length);
    }

    /**
     * Generate index name to use when you want create a new index.
     *
     * @param string $table
     * @param array  $columns
     * @param bool   $isPrimaryKey
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function generateIndexName(string $table, array $columns, bool $isPrimaryKey = false)
    {
        $tableSection = str_replace(['{', '}', '%'], '', $table);
        $tableSection = Inflector::camelize($tableSection);

        $fieldSection = '';
        foreach ($fields as $field){
            $fieldSection .= Inflector::camelize($field) .'_';
        }

        if ($isPrimaryKey) {
            return $tableSection . '_pk';
        }

        return $tableSection . '-' . $fieldSection . 'index';
    }

    /**
     * Create a primary key and generate name.
     *
     * @param $table
     * @param $columns
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function addNamedPrimaryKey($table, $columns): void
    {
        $this->addPrimaryKey(
            $this->generateIndexName($table, is_array($columns) ? $columns : [$columns], true),
            $table,
            $columns
        );
    }

    /**
     * Create an Index and generate it's name.
     *
     * @param      $table
     * @param      $columns
     * @param bool $unique
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function createNamedIndex($table, $columns, $unique = false): void
    {
        $this->createIndex(
            $this->generateIndexName($table, is_array($columns) ? $columns : [$columns], false),
            $table,
            $columns,
            $unique
        );
    }
}