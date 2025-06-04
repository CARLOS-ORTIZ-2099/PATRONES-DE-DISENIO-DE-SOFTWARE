<?php



/* La interfaz Builder declara un conjunto de métodos para ensamblar una consulta SQL.
   Todos los pasos de construcción devuelven el objeto constructor actual y permiten el
   encadenamiento: $builder->select(...)->where(...).
*/


interface SQLQueryBuilder
{
  public function select(string $table, array $fields): SQLQueryBuilder;

  public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder;

  public function limit(int $start, int $offset): SQLQueryBuilder;

  // +100 otros métodos de sintaxis SQL...

  public function getSQL(): string;
}


/*Cada Constructor Concreto corresponde a un dialecto SQL específico y puede implementar
  los pasos del constructor de forma ligeramente diferente a los demás.
 
  Este Constructor de Concreto puede generar consultas SQL compatibles con MySQL.
*/


class MysqlQueryBuilder implements SQLQueryBuilder
{
  protected $query;

  protected function reset(): void
  {
    $this->query = new \stdClass();
  }


  //  Construir una consulta SELECT base.

  public function select(string $table, array $fields): SQLQueryBuilder
  {
    $this->reset();
    // aqui estamos creando propiedades dinamicas para nuestro objeto, esto es
    // posible gracias a que en un objeto StdClass si podemos crearlas asi
    $this->query->base = "SELECT " . implode(", ", $fields) . " FROM " . $table;
    $this->query->type = 'select';
    // el metodo select deberia retornar un tipo SQLQueryBuilder, y lo que 
    // retornamos es this es decir un objeto de la instancia de 
    // MysqlQueryBuilder que implementa de SQLQueryBuilder por lo tanto esta bien
    return $this;
  }

  // Añade una condición WHERE.
  public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder
  {
    if (!in_array($this->query->type, ['select', 'update', 'delete'])) {
      throw new \Exception("WHERE can only be added to SELECT, UPDATE OR DELETE");
    }
    $this->query->where[] = "$field $operator '$value'";

    return $this;
  }


  // Añade una restricción LIMIT.

  public function limit(int $start, int $offset): SQLQueryBuilder
  {
    if (!in_array($this->query->type, ['select'])) {
      throw new \Exception("LIMIT can only be added to SELECT");
    }
    $this->query->limit = " LIMIT " . $start . ", " . $offset;

    return $this;
  }


  // Obtenga la cadena de consulta final.

  public function getSQL(): string
  {
    $query = $this->query;
    $sql = $query->base;
    if (!empty($query->where)) {
      $sql .= " WHERE " . implode(' AND ', $query->where);
    }
    if (isset($query->limit)) {
      $sql .= $query->limit;
    }
    $sql .= ";";
    return $sql;
  }
}


/*Este Constructor de Concreto es compatible con PostgreSQL. Si bien Postgres es muy
  similar a MySQL, presenta varias diferencias. Para reutilizar el código común,
  lo extendemos del Constructor de MySQL, pero sobrescribimos algunos de los pasos de 
  construcción.
 
*/



class PostgresQueryBuilder extends MysqlQueryBuilder
{

  // Entre otras cosas, PostgreSQL tiene una sintaxis LIMIT ligeramente diferente.

  public function limit(int $start, int $offset): SQLQueryBuilder
  {
    parent::limit($start, $offset);

    $this->query->limit = " LIMIT " . $start . " OFFSET " . $offset;

    return $this;
  }

  // + toneladas de otras anulaciones...
}

/*Tenga en cuenta que el código cliente usa directamente el objeto constructor. En este 
 caso, no es necesaria una clase Director designada, ya que el código cliente necesita
 consultas diferentes casi siempre, por lo que la secuencia de pasos de construcción no
 se puede reutilizar fácilmente.

 Dado que todos nuestros constructores de consultas crean productos del mismo tipo (una cadena),
 podemos interactuar con todos ellos mediante su interfaz común.Más adelante, si implementamos 
 una nueva clase Constructor, podremos pasar su instancia al código cliente existente sin 
 interrumpirlo gracias a la interfaz SQLQueryBuilder.
*/

function clientCode(SQLQueryBuilder $queryBuilder)
{


  $query = $queryBuilder
    ->select("users", ["name", "email", "password"])
    ->where("age", 18, ">")
    ->where("age", 30, "<")
    ->limit(10, 20)
    ->getSQL();


  echo $query;
}


/* La aplicación selecciona el tipo de generador de consultas adecuado según la configuración actual
   o la configuración del entorno.
*/


// if ($_ENV['database_type'] == 'postgres') {
//     $builder = new PostgresQueryBuilder(); } else {
//     $builder = new MysqlQueryBuilder(); }
//
// clientCode($builder);



debuguear("Testing MySQL query builder:\n");
clientCode(new MysqlQueryBuilder());



debuguear("Testing PostgresSQL query builder:\n");
clientCode(new PostgresQueryBuilder());
