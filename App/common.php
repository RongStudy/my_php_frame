<?php
declare(strict_types=1);

function is_cli()
{
    return preg_match("/cli/i", php_sapi_name()) ? true : false;
}

function br()
{
    echo is_cli() ? "<br>" : "\r\n";
}

function make_insert_sql(string $table, array $data): string
{
    $columns = '';
    $values = '';
    foreach ($data as $key => $value) {
        $columns .= "`{$key}`, ";
        $values .= "'{$value}', ";
    }
    $columns = substr($columns, 0, -2);
    $values = substr($values, 0, -2);
    return "INSERT INTO `{$table}` ({$columns}) VALUES ({$values})";
}

function make_update_sql(string $table, array $data, string $where)
{
    $columns = '';
    if (empty($data)) {
        throw_except(__FUNCTION__ . ' $data can\'t is empty');
    }
    if (empty($table)) {
        throw_except(__FUNCTION__ . ' $table can\'t is empty');
    }
    if (empty($where)) {
        throw_except(__FUNCTION__ . ' $where can\' is empty');
    }
    foreach ($data as $key => $value) {
        $columns .= " `{$key}` = '{$value}',";
    }
    $columns = substr($columns, 0, -1);
    return "UPDATE `{$table}` SET {$columns} WHERE " . $where;
}

function throw_except(string $info, int $code = 0)
{
    if (IS_TEST){
        throw new \Exception($info, 0);
    } else {
        exit('Whoops, 发生了错误 !!!');
    }
}

function create_sql($array = array(), $table = '', $field = '', $perSqlNum = 10)
{
    if (empty($array)) {
        exit('入库数组为空');
    }
    $fieldArray = explode(',', $field); # 入库字段
    $resArray = array();
    foreach ($array as $key => $value) {
        $valueStr = '(';
        foreach ($fieldArray as $fk => $fv) {
            if ($value[$fv] === 'null' || $value[$fv] === false || $value[$fv] === null) {
                $value[$fv] = "";
            }
            if (!is_numeric($value[$fv])) {
                $value[$fv] = "'{$value[$fv]}'";
            }
            $valueStr .= ($value[$fv] . ',');
        }
        $valueStr = substr($valueStr, 0, -1);
        $valueStr .= ')';
        $resArray[] = $valueStr;
    }
    $resArray = array_chunk($resArray, $perSqlNum);

    $return = [];
    foreach ($resArray as $key => $value) {
        $sql = "insert into {$table} ($field) values " . implode(',', $value) . ';';
        $return[] = $sql;
    }
    return $return;
}