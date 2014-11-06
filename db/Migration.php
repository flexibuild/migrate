<?php

namespace flexibuild\migrate\db;

use yii\base\InvalidParamException;
use yii\db\Migration as BaseMigration;

/**
 * Base application migration class with some helpful features for writing yii2 migrations.
 * For using extend your migration class from this class.
 * 
 * Helpful features of the class:
 * - helpful constants, see [[NOT_NULL]], [[DEFAULT_*]] and [[FK_*]] constants for more info.
 * - method [[typeEnum]] for more quickly defining ENUM columns.
 * @see typeEnum()
 * - methods for creating/dropping indexes/foreign keys with auto generated names.
 * @see createIndexAutoNamed()
 * @see dropIndexAutoNamed()
 * @see addForeignKeyAutoNamed()
 * @see dropForeignKeyAutoNamed()
 * @see addPrimaryKeyAutoNamed()
 * @see dropPrimayKeyAutoNamed()
 * 
 * In addition:
 * - if [[$autoWrapTableNames]] is true, table names will automatically wrapped like `{{%table_name}}`
 * @see $autoWrapTableNames
 * - method createTable automatically adds character set and collate for mysql database.
 * @see createTable()
 * 
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class Migration extends BaseMigration
{
    /**
     * Constants for using in column types.
     * For example:
     * ```
     *  $this->createTable('{{%customer}}', [
     *      'id' => 'pk',
     *      'customer_name' => Schema::TYPE_STRING . self::NOT_NULL,
     *      'is_active' => Schema::TYPE_BOOLEAN . self::NOT_NULL . self::DEFAULT_TRUE,
     *      ...
     *  ]);
     * ```
     */
    const NOT_NULL = ' NOT NULL';
    const DEFAULT_NULL = ' DEFAULT NULL';
    const DEFAULT_TRUE = ' DEFAULT 1';
    const DEFAULT_FALSE = ' DEFAULT 0';
    const DEFAULT_0 = ' DEFAULT 0';
    const DEFAULT_1 = ' DEFAULT 1';
    const DEFAULT_ = ' DEFAULT ';

    /**
     * Const that used in `truncateLongName()` for truncating long names.
     */
    const MAX_NAME_LENGTH = 64;

    const PREFIX_FOREIGN_KEY = 'fk_';
    const PREFIX_FOREIGN_KEY_INDEX = 'fkidx_';
    const PREFIX_INDEX = 'idx_';
    const PREFIX_UNIQUE_INDEX = 'uidx_';
    const PREFIX_PRIMARY_KEY = 'pk_';

    /**
     * Constants for foreign keys onDelete, onUpdate types values.
     */
    const FK_RESTRICT = 'RESTRICT';
    const FK_CASCADE = 'CASCADE';
    const FK_NO_ACTION = 'NO ACTION';
    const FK_SET_DEFAULT = 'SET DEFAULT';
    const FK_SET_NULL = 'SET NULL';

    /**
     * @var boolean whether need auto wrap table names like `{{%table_name}}`.
     */
    public $autoWrapTableNames = true;

    /**
     * Wraps table name by `{{%name}}` brackets if it has not been wrapped yet.
     * @param string $tableName input table.
     * @return string wrapped table name.
     */
    protected function wrapTableName($tableName)
    {
        $tableName = '{{%'.rtrim(ltrim($tableName, '{%'), '}').'}}';
        return $tableName;
    }

    /**
     * It method sees `$autoWrapTableNames` param. If it is true than table name will be wrapped.
     * @see self::$autoWrapTableNames
     * @see wrapTableName()
     * 
     * @param string $tableName input table name.
     * @return string wrapped table name.
     */
    protected function autoWrappedTableName($tableName)
    {
        if ($this->autoWrapTableNames) {
            return $this->wrapTableName($tableName);
        }
        return $tableName;
    }

    /**
     * Generates SQL type string for ENUM columns. You may be will want to use it
     * when you will create table with `createTable()` method.
     * @param array $values all possible ENUM values.
     * @param string|null $default default column value. NULL by default.
     * @param boolean $notNull whether it column can be null or not.
     * @return string generated SQL string.
     * @throws InvalidParamException
     */
    public function typeEnum($values, $default = null, $notNull = false)
    {
        if ($default === null) {
            if ($notNull) {
                throw new InvalidParamException('Cannot create not null property with default null value.');
            }
        } else {
            if (!in_array($default, $values, true)) {
                throw new InvalidParamException("Default value '$default' was not found in values list.");
            }
        }

        $db = $this->db;
        $result = 'ENUM('.implode(', ', array_map(function ($value) use ($db) {
            return $db->getSchema()->quoteValue($value);
        }, $values)).')';

        if ($notNull) {
            $result .= self::NOT_NULL;
        }
        if ($default === null) {
            $result .= self::DEFAULT_NULL;
        } else {
            $result .= self::DEFAULT_.$db->getSchema()->quoteValue($default);
        }

        return $result;
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function insert($table, $columns)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::insert($table, $columns);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function batchInsert($table, $columns, $rows)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::batchInsert($table, $columns, $rows);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function update($table, $columns, $condition = '', $params = [])
    {
        $table = $this->autoWrappedTableName($table);
        return parent::update($table, $columns, $condition, $params);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function delete($table, $condition = '', $params = [])
    {
        $table = $this->autoWrappedTableName($table);
        return parent::delete($table, $condition, $params);
    }

    /**
     * @inheritdoc
     * Notes:
     *  - table will be auto pefixied if [[$autoWrapTableNames]] is true.
     *  - options will be auto set by character set and collate for mysql db.
     */
    public function createTable($table, $columns, $options = null)
    {
        if ($options === null && $this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $charset = $this->db->charset;
            $collate = $charset . '_unicode_ci';
            $options = "CHARACTER SET $charset COLLATE $collate ENGINE=InnoDB";
        }
        $table = $this->autoWrappedTableName($table);
        return parent::createTable($table, $columns, $options);
    }

    /**
     * @inheritdoc
     * Note: table names will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function renameTable($table, $newName)
    {
        $table = $this->autoWrappedTableName($table);
        $newName = $this->autoWrappedTableName($newName);
        return parent::renameTable($table, $newName);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function dropTable($table)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::dropTable($table);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function truncateTable($table)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::truncateTable($table);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function addColumn($table, $column, $type)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::addColumn($table, $column, $type);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function dropColumn($table, $column)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::dropColumn($table, $column);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function renameColumn($table, $name, $newName)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::renameColumn($table, $name, $newName);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function alterColumn($table, $column, $type)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::alterColumn($type, $column, $type);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function addPrimaryKey($name, $table, $columns)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::addPrimaryKey($name, $table, $columns);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function dropPrimaryKey($name, $table)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::dropForeignKey($name, $table);
    }

    /**
     * @inheritdoc
     * Note: tables will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null)
    {
        $table = $this->autoWrappedTableName($table);
        $refTable = $this->autoWrappedTableName($refTable);
        return parent::addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function dropForeignKey($name, $table)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::dropForeignKey($name, $table);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function createIndex($name, $table, $columns, $unique = false)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::createIndex($name, $table, $columns, $unique);
    }

    /**
     * @inheritdoc
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     */
    public function dropIndex($name, $table)
    {
        $table = $this->autoWrappedTableName($table);
        return parent::dropIndex($name, $table);
    }

    /**
     * Implodes columns with `__` separator. It will removes all special chars
     * like '{', '}', '[', ']' and others.
     * @param string|array $columns the columns to be processed
     * @return string underscopes separated columns.
     */
    protected function implodeColumns($columns)
    {
        $charsForDeletion = ['{', '}', '[', ']', '"', "'", '(', ')', ' ', '`'];
        $columnsStr = strtr($this->db->getSchema()->getQueryBuilder()->buildColumns($columns), array_fill_keys($charsForDeletion, ''));
        return implode('__', explode(',', $columnsStr));
    }

    /**
     * Truncates long name. If name length is bigger than `MAX_NAME_LENGTH` const
     * the method will truncate it and will add name hash at the end of them.
     * @param string $name Name for checking and truncating if need.
     * @return string Result truncated if need name.
     */
    public function truncateLongName($name)
    {
        if (strlen($name) > static::MAX_NAME_LENGTH) {
            $hash = sha1($name);
            $name = substr($name, 0, static::MAX_NAME_LENGTH - 3 - strlen($hash)) . "___$hash";
        }
        return $name;
    }

    /**
     * Generates name for index by it columns and unique type.
     * @param string $table the table that the new index will be created for. The table name will be properly quoted by the method.
     * @param string|array $columns the column(s) that should be included in the index. This property in same format as in `createIndex()` method.
     * @param string $prefix the string that the result name will be prefixed. By default `static::PREFIX_INDEX` will be used.
     * @return string generated index name.
     */
    public function generateIndexName($table, $columns, $prefix = null)
    {
        $name = $prefix === null ? static::PREFIX_INDEX : $prefix;
        $name .= $this->db->getSchema()->getRawTableName($table) . '___';
        $name .= $this->implodeColumns($columns);
        return $this->truncateLongName($name);
    }

    /**
     * Generates name for foreign key by it columns and ref table with columns.
     * @param string $table the table that the foreign key constraint will be added to.
     * @param string $columns the name of the column to that the constraint will be added on. This property in same format as in `addForeignKey()` method.
     * @param string $refTable the table that the foreign key references to.
     * @param string $refColumns the name of the column that the foreign key references to. . This property in same format as in `addForeignKey()` method.
     */
    public function generateForeignKeyName($table, $columns, $refTable, $refColumns)
    {
        $name = static::PREFIX_FOREIGN_KEY;
        $name .= $this->db->getSchema()->getRawTableName($table) . '__';
        $name .= $this->implodeColumns($columns) . '___';
        $name .= $this->db->getSchema()->getRawTableName($refTable) . '__';
        $name .= $this->implodeColumns($refColumns);
        return $this->truncateLongName($name);
    }

    /**
     * Builds and executes a SQL statement for creating a new index. Name for index will be automatically generated.
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     * 
     * @param string $table the table that the new index will be created for. The table name will be properly quoted by the method.
     * @param string|array $columns the column(s) that should be included in the index. If there are multiple columns, please separate them
     * by commas or use an array. The column names will be properly quoted by the method.
     * @param boolean $unique whether to add UNIQUE constraint on the created index.
     */
    public function createIndexAutoNamed($table, $columns, $unique = false)
    {
        $table = $this->autoWrappedTableName($table);
        $name = $this->generateIndexName($table, $columns, $unique ? static::PREFIX_UNIQUE_INDEX : static::PREFIX_INDEX);
        $this->createIndex($name, $table, $columns, $unique);
    }

    /**
     * Builds and executes a SQL statement for dropping an index that created with `createIndexAutoNamed()` method.
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     * 
     * @param string $table the table that the index created for. The table name will be properly quoted by the method.
     * @param string|array $columns the column(s) that included in the index. If there are multiple columns, please separate them
     * by commas or use an array. The column names will be properly quoted by the method.
     * @param boolean $unique whether the index used UNIQUE constraint.
     */
    public function dropIndexAutoNamed($table, $columns, $unique = false)
    {
        $table = $this->autoWrappedTableName($table);
        $name = $this->generateIndexName($table, $columns, $unique ? static::PREFIX_UNIQUE_INDEX : static::PREFIX_INDEX);
        $this->dropIndex($name, $table);
    }

    /**
     * Builds a SQL statement for adding a foreign key constraint to an existing table. Name for foreign key will be automatically generated.
     * The method will properly quote the table and column names.
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     * 
     * @param string $table the table that the foreign key constraint will be added to.
     * @param string $columns the name of the column to that the constraint will be added on. If there are multiple columns, separate them with commas or use an array.
     * @param string $refTable the table that the foreign key references to.
     * @param string $refColumns the name of the column that the foreign key references to. If there are multiple columns, separate them with commas or use an array.
     * @param boolean $createIndex whether method should create index before creating foreign key or not.
     * @param string $delete the ON DELETE option. RESTRICT by default. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
     * @param string $update the ON UPDATE option. RESTRICT by default. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
     */
    public function addForeignKeyAutoNamed($table, $columns, $refTable, $refColumns, $createIndex = true, $delete = self::FK_RESTRICT, $update = self::FK_RESTRICT)
    {
        $table = $this->autoWrappedTableName($table);
        $refTable = $this->autoWrappedTableName($refTable);

        if ($createIndex) {
            $name = $this->generateIndexName($table, $columns, static::PREFIX_FOREIGN_KEY_INDEX);
            $this->createIndex($name, $table, $columns);
        }

        $name = $this->generateForeignKeyName($table, $columns, $refTable, $refColumns);
        $this->addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);
    }

    /**
     * Builds a SQL statement for dropping a foreign key constraint that created with `addForeignKeyAutoNamed()` method.
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     * 
     * @param string $table the table that the foreign key constraint will be dropped to.
     * @param string $columns the name of the column to that the constraint added on. If there are multiple columns, separate them with commas or use an array.
     * @param string $refTable the table that the foreign key references to.
     * @param string $refColumns the name of the column that the foreign key references to. If there are multiple columns, separate them with commas or use an array.
     * @param boolean $dropCreatedIndex whether method should drop index that was auto created by passing $createIndex == true in `addForeignKeyAutoNamed()` method.
     */
    public function dropForeignKeyAutoNamed($table, $columns, $refTable, $refColumns, $dropCreatedIndex = true)
    {
        $table = $this->autoWrappedTableName($table);
        $refTable = $this->autoWrappedTableName($refTable);

        $name = $this->generateForeignKeyName($table, $columns, $refTable, $refColumns);
        $this->dropForeignKey($name, $table);

        if ($dropCreatedIndex) {
            $name = $this->generateIndexName($table, $columns, static::PREFIX_FOREIGN_KEY_INDEX);
            $this->dropIndex($name, $table);
        }
    }

    /**
     * Builds and executes a SQL statement for creating a primary key.  Name for primary key will be automatically generated.
     * The method will properly quote the table and column names.
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     * 
     * @param string $table the table that the primary key constraint will be added to.
     * @param string|array $columns comma separated string or array of columns that the primary key will consist of.
     */
    public function addPrimaryKeyAutoNamed($table, $columns)
    {
        $table = $this->autoWrappedTableName($table);
        $name = $this->generateIndexName($table, $columns, static::PREFIX_PRIMARY_KEY);
        $this->addPrimaryKey($name, $table, $columns);
    }

    /**
     * Builds and executes a SQL statement for dropping a primary key that created with `addPrimaryKeyAutoNamed()` method.
     * Note: table will be auto pefixied if [[$autoWrapTableNames]] is true.
     * 
     * @param string $table the table that the primary key constraint will be removed from.
     * @param string|array $columns comma separated string or array of columns that the primary key is consist of.
     */
    public function dropPrimaryKeyAutoNamed($table, $columns)
    {
        $table = $this->autoWrappedTableName($table);
        $name = $this->generateIndexName($table, $columns, static::PREFIX_PRIMARY_KEY);
        $this->dropPrimaryKey($name, $table);
    }
}
