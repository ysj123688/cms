<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $labelsPadding number */
/* @var $enumLabels string[][] list of enum columns (name => [values]) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php foreach ($enumLabels as $name => $values): ?>
<?php foreach ($values as $value): ?>
    const <?= strtoupper("{$name}_${value}") ?> = <?= "'$value'" ?>;
<?php endforeach; ?>
<?php endforeach; ?>

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= str_pad("'$name'", $labelsPadding, ' ', STR_PAD_RIGHT)."=> " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
    
<?php foreach ($enumLabels as $name => $values): ?>
	/**
     * @return string[]
     */
    public function <?= "{$name}Labels" ?>()
	{
        return [
<?php foreach ($values as $value): ?>
            self::<?= strtoupper("{$name}_${value}") ?> => <?= "'$value'" ?>,
<?php endforeach; ?>
        ];
    }
<?php endforeach; ?>
    
    /* ------------------------------------------------------------------------
     * ActiveQuery calls
     * ------------------------------------------------------------------------ */
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>

    /* ------------------------------------------------------------------------
     * Utilities
     * ------------------------------------------------------------------------ */

<?php foreach ($enumLabels as $name => $values): ?>
    /**
     * @return string
     */
    public function <?= "get".ucfirst($name)."Label" ?>()
    {
        if ($this-><?= $name ?> === null || empty($this-><?= $name ?>)) {
            return null;
        }
        return $this-><?= "{$name}Labels()[\$this->{$name}]" ?>;
    }
    
<?php foreach ($values as $value): ?>
    /**
     * @return boolean
     */
    public function <?= "is".ucfirst($value) ?>()
    {
        return $this-><?= "{$name}" ?> === self::<?= strtoupper("{$name}_${value}") ?>;
    }
<?php endforeach; ?>
<?php endforeach; ?>
}

<?php if ($queryClassName): ?>
<?php 
$modelFullClassName = $className;
if ($generator->ns !== $generator->queryNs) {
    $modelFullClassName = '\\' . $generator->ns . '\\' . $modelFullClassName;
}
?>
/**
 * This is the ActiveQuery class for [[<?= $modelFullClassName ?>]].
 *
 * @see <?= $modelFullClassName . "\n" ?>
 */
class <?= $queryClassName ?> extends <?= '\\' . ltrim($generator->queryBaseClass, '\\') . "\n" ?>
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

<?php foreach ($enumLabels as $name => $values): ?>
<?php foreach ($values as $value): ?>
    /**
     * @return <?= $queryClassName . "\n" ?>
     */
    public function <?= "{$name}".ucfirst($value) ?>()
    {
        return $this->andWhere([<?= "'$name'" ?> => <?= "{$modelFullClassName}::".strtoupper("{$name}_${value}") ?>]);
    }
<?php endforeach; ?>
<?php endforeach; ?>

    /**
     * @inheritdoc
     * @return <?= $modelFullClassName ?>[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return <?= $modelFullClassName ?>|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
<?php endif; ?>
