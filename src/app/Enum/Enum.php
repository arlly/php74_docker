<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2018/09/10
 * Time: 10:32
 */
namespace App\Enum;

use ReflectionClass;

abstract class Enum
{
    // Enumを放り込んでおく場所です
    private static $enumArray = array();

    // 値の名前
    private $name = null;

    // 値
    private $value = null;

    // public には new させません
    private function __construct()
    {
    }

    private function setName($name)
    {
        $this->name = $name;
    }

    private function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * 名前を直に指定してアクセスしたい時に利用します
     * @param $name Enumの名前です
     * @param $args 今回は使いません
     * @return 対象のインスタンスです
     */
    final public static function __callStatic($name, $args)
    {
        if ($args) {
            throw new Exception('undefined method');
        }
        return self::valueOf($name);
    }

    /**
     * 全てのEnumを返却
     * @return 該当Enumの配列
     */
    final public static function values()
    {
        $className = get_called_class();
        if (!isset(self::$enumArray[$className])) {
            $class = new ReflectionClass($className);
            $history = array();
            foreach ($class->getConstants() as $key => $val) {
                if (in_array($val, $history)) {
                    throw new Exception('値の重複');
                }
                $enum = new $className;
                $enum->setName($key);
                $enum->setValue($val);
                self::$enumArray[$className][] = $enum;
                $history[] = $val;
            }
        }
        return self::$enumArray[$className];
    }

    /**
     * 名前から該当Enumを探して返却
     * @param $name 名前
     * @return Enumのオブジェクト
     */
    final public static function valueOf($name)
    {
        $result = null;
        foreach (self::values() as $enum) {
            if ($enum->name() === $name) {
                $result = $enum;
                break;
            }
        }
        return $result;
    }

    /**
     * 値から該当Enumを探して返却
     * @param $value 値
     * @return Enumのオブジェクト
     */
    final public static function nameOf($value)
    {
        $result = null;
        foreach (self::values() as $enum) {
            if ($enum->value() === $value) {
                $result = $enum;
                break;
            }
        }
        return $result;
    }


    /**
     * 名前を返却
     * @return 値の名前を文字列で
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * 値を返却
     * @return 値
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * 比較する
     * @param $enum 比べる対象
     * @return boolean 一致ならtrue
     */
    public function equals(Enum $enum)
    {
        return $this->name() === $enum->name() &&
            $this->value() === $enum->value() &&
            get_class($this) === get_class($enum);
    }
}
