# PHP Db Faker
为数据库快速制作测试数据! 

* 基于开源fzaninotto/faker二次开发
* 轻松将生成器映射到数据库
* 支持关联表
* 为表生成数据的两种方法: generator或array
* 可以自定义generator


## 安装

首选安装方式通过 [composer](http://getcomposer.org/download/). 安装

```bash
$ composer require zangyd/dbfaker
```

或者

```
"zangyd/dbfaker": "*"
```

增加必须文件到 `composer.json` 

## 快速开始

你可以像下面这样快速生成数据在数据库中:

```php
$pdo = new PDO('mysql:localhost', 'root', 'test');
$seeder = new \zangyd\dbfaker\Seeder($pdo);
$generator = $seeder->getGeneratorConfigurator();
$faker = $generator->getFakerConfigurator();

$seeder->table('article')->columns([
    'id', //automatic pk
    'book_id', //automatic fk
    'name'=>$faker->firstName,
    'content'=>$faker->text
        ])->rowQuantity(30);


$seeder->table('book')->columns([
    'id',
    'name'=>$faker->text(20),
])->rowQuantity(30);

$seeder->table('category')->columns([
    'id',
    'book_id',
    'name'=>$faker->text(20),
    'type'=>$faker->randomElement(['shop','cv','test']),
])->rowQuantity(30);

$seeder->refill();
```

!!! 注意： `$seeder->refill();` 这会用随机生成的数据填充你的数据库，在生产环境中，会删除你的敏感数据。

首先，创建一个pdo连接，并用它初始化新的`$seeder'对象。然后创建generator和faker包装器实例。然后配置每个表。重新填充所有已配置的表。

## 从generator填充表

为每个列配置合适的generator. Generators 有五种类型:
1) 主键. 自增主键 (暂时不支持组合键)
2) 关联. 关联到其他表
3) 任意generator
4) 匿名函数
5) 常量

1）、主键。如果表的主键是'id'，只写'id'即可。系统自动将其作为主键处理。如果不是'id'，则必须使用完整语法来配置PK

```php
...
'book_id'=> $generator->pk
...
```

2) 关系。当前关系是指从另一个表或当前表中的某列随机填充。

如果关系正好是“表名_id”，并且该表也正配置中，则可以保持原样。系统会自动检测并设置正确的关系。否则，你必须使用完整的语法。下面是从表'book'设置列parent_id的示例，是从'book_category'表填充的。id列：

```php
...
'parent_id'=>$generator->relation('book_category', 'id'),
...
```

3) Faker generators

generator很容易使用。

示例

```php
...
'first_name'=>$faker->firstName,
'preview'=>$faker->text(20),
'content'=>$faker->text,
'type'=>$faker->randomElement(['shop','cv','test']),
...
```

4) 匿名函数或其他可调用函数
匿名函数只需返回一些可以写入相应数据库列的标量值。

明显的例子是：

```php
'user_id'=>function() {
  return rand(1, 234343);
}
```

5) 常量
每行的该字段使用相同的值。

```php
'is_active'=>1
```

注意：不能将这些值用作普通值类型：

*任何php可调用字符串（它们将被调用）

* '主键'
* '关系'
* 'generator'

## 从数组填充数据
也可以直接从数组填充表。

对于具有数字键的数组，必须显式定义相应的列名。如果不想使用某些列，只需对列配置使用false/null。

```php
$array =
 [
    [1,'twinsen','the heir'],
    [2,'zoe', 'self-occupied'],
    [3, 'baldino', 'twinsunian elephant']
 ];
 $columnConfig = [false,'name','occupation'];
 
$seeder->table('users')->data($array, $columnConfig)->rowQuantity(30);
```

数据数量

可以为每个表设置所需的行数量。如果未设置，将使用默认值（30）。从数组填充的逻辑是：如果不定义行数量，则将填充所有数组。如果在提供的数组中使用大于“行”数量的值，则给定数组将被迭代以填充所需的行。如果提供的值小于数组的行数，则将使用该行数。

## 如何工作
当你运行下面这段代码

```php
$seeder->table('category')->columns([
    'id',
    'book_id',
    'name'=>$faker->text(20),
    'type'=>$faker->randomElement(['shop','cv','test']),
])->rowQuantity(30);
```

数据库什么也没做。当调用“refill()”方法时，才会开始填充不依赖于其他方法的表。然后迭代填充依赖表。



