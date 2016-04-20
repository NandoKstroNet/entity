#Entity

Pequena documentação da classe Entity.

##Requerimentos
PHP 5.3+

##Como usar
Suponhamos que você esteja utilizando uma classe chamada produtos. Para ter acesso ao crud oferecido por pela classe Entity, basta que você estenda a mesma e defina a table da classe atual. Veja:

```php
<?php 
namespace CodeExperts\Product;

use CodeExperts\Db\Entity;

class Product extends Entity
{
    /**
     * Table
     * /
     protected $table = 'products';
```
Utilizando:

```php
<?php
use CodeExperts\Product\Product;

new Product(new \PDO(...));
```
O construct do nosso Entity têm a dependencia de um cara do tipo \PDO, ou seja, uma instância de PDO ou classes que derivem de, irão funcionar.

##Fetch All
Para resgatar todos, basta utilizar o método `getAll()`. Veja a seguir:


```php
<?php
...
$products = new Product(new \PDO(...));

var_dump($products->getAll());
```
##Where
O método where permite a busca de determinados sets de dados segundo condição desejada. Exemplo:

```php
<?php
...
$products = new Product(new \PDO(...));

var_dump($products->where(['slug' => 'playstation-4'));
```
##Find
Você pode utilizar o método `find`para buscar um dado especifico, pelo ID do mesmo. Veja a seguir:

```php
<?php
...
$products = new Product(new \PDO(...));

var_dump($products->find(30));
```
##Salvando & Atualizando dados
A entity possui uma interface única de save através do método `save()`, por meio dela é possivel inserir ou editar um dado. Veja abaixo os exemplos:

###Inserindo

```php
<?php
...
$products = new Product(new \PDO(...));
$product = array(
             'name'  => 'Kindle Fire',
             'price' => '19.90'
             );     
$products->save($product);

```
###Atualizando
```php
<?php
...
$products = new Product(new \PDO(...));
$product = array(
             'id'    => 10
             'name'  => 'Kindle Fire',
             'price' => '19.90'
             );     
$products->save($product);

```
Obs.: As keys do array associativo são os nomes dos campos no seu bd.

#Delete
Por meio do método `delete`, e sem muitos rodeios, você consegue deletar um determinado dado pelo seu ID. Exemplo:

```php
<?php
...
$products = new Product(new \PDO(...));
$products->delete(10);

```
#Licença
Lib compartilhada sob a licença MIT. Mais informações [aqui](LICENSE.md).

