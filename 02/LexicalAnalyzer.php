<?php
/**
 * 词法分析01 只作了解析 age >= 45
 * 没有做更多的，后续会补充
 */


class DfaState
{
    const Initial = 'Initial'; // 初始化状态，初始状态进入这个状态 或者 当一个状态结束之后进入这个状态
    const ID = 'Identifier'; // Identifier 标识符 age >= 45 中的 age
    const GT = 'GT'; // 大于号
    const GE = 'GE'; // 大于等于
    const IntLiteral = 'IntLiteral'; // 数值
    const Id_int1 = 'Id_int1'; // int 状态1
    const Id_int2 = 'Id_int2'; // int 状态2
    const Id_int3 = 'Id_int3'; // int 状态3
    const INT = 'int'; // int 保留字
    const EQ = 'EQ';
    const PLUS = '+';
    const MINUS = '-';
    const STAR = '*';
    const SLASH = '/';
}

class Token
{
    public $type;
    public $value;
}

function isAplha($ch)
{
    return preg_match('~[a-zA-Z_]~', $ch);
}

function isNumber($ch)
{
    return preg_match('~[0-9]~', $ch);
}

function isGt($ch)
{
    return $ch == '>';
}

function isEq($ch)
{
    return $ch == '=';
}

function saveToken($tokenArr, $type, $value)
{
    $token = new Token();
    $token->type = $type;
    $token->value = $value;
    $tokenArr[] = $token;

    return $tokenArr;
}

$str = 'age>=45';
$str = 'int age = 40';
$str = 'intA = 10';
$str = '2+3*5';

$chars = str_split($str);
$type = DfaState::Initial;
$lastType = DfaState::Initial;
$value = '';

$tokenArr = [];

foreach ($chars as $ch) {
    switch ($type) {
        case DfaState::Initial:
            break;
        case DfaState::ID:
            if (isAplha($ch) || isNumber($ch)) { // 是字母或数组就继续存下去
                $value .= $ch;
            } else { // 否则进入初始化状态
                $lastType = $type;
                $type = DfaState::Initial;
            }
            break;
        case DfaState::IntLiteral:
            if (isNumber($ch)) { // 是数字则继续保持这个状态
                $value .= $ch;
            } else {// 否则进入初始化状态
                $lastType = $type;
                $type = DfaState::Initial;
            }
            break;
        case DfaState::GT:
            if (isEq($ch)) { // 是等号则切换到 GE 状态
                $value .= $ch;
                $type = DfaState::GE;
            } else {// 否则进入初始化状态
                $lastType = $type;
                $type = DfaState::Initial;
            }
            break;
        case DfaState::GE: // 到这个类型之后就只会直接结束进入初始化状态了
        case DfaState::EQ: // 到这个类型之后就只会直接结束进入初始化状态了
        case DfaState::PLUS: // 到这个类型之后就只会直接结束进入初始化状态了
        case DfaState::MINUS: // 到这个类型之后就只会直接结束进入初始化状态了
        case DfaState::STAR: // 到这个类型之后就只会直接结束进入初始化状态了
        case DfaState::SLASH: // 到这个类型之后就只会直接结束进入初始化状态了
            $lastType = $type;
            $type = DfaState::Initial;
            break;
        case DfaState::Id_int1: // int 状态2
            if (isAplha($ch) || isNumber($ch)) { // 是字母或数组就继续存下去
                if ($ch == 'n') { // int 状态1
                    $type = DfaState::Id_int2;
                }
                $value .= $ch;
            } else { // 否则进入初始化状态
                $lastType = $type;
                $type = DfaState::Initial;
            }
            break;
        case DfaState::Id_int2: // int 状态2
            if (isAplha($ch) || isNumber($ch)) { // 是字母或数组就继续存下去
                if ($ch == 't') { // int 状态2
                    $type = DfaState::Id_int3;
                }
                $value .= $ch;
            } else { // 否则进入初始化状态
                $lastType = $type;
                $type = DfaState::Initial;
            }
            break;
        case DfaState::Id_int3: // int 状态3
            var_dump('aaaaaaaaa');
            if (isAplha($ch) || isNumber($ch)) { // 是字母或数组就继续存下去，并且把状态变成ID
                $type = DfaState::ID;
                $value .= $ch;
            } else { // 否则进入初始化状态
                $lastType = DfaState::INT;
                $type = DfaState::Initial;
            }
            break;
    }
    if ($type == DfaState::Initial) {
        if ($value) {
            // 如果 value 有值就存储一下
            $tokenArr = saveToken($tokenArr, $lastType, $value);
            $value = '';
        }

        if ($ch == 'i') { // int 状态1
            $type = DfaState::Id_int1;
            $value .= $ch;
        } else if (isAplha($ch)) {
            $type = DfaState::ID;
            $value .= $ch;
        } else if (isNumber($ch)) {
            $type = DfaState::IntLiteral;
            $value .= $ch;
        } else if (isGt($ch)) {
            $type = DfaState::GT;
            $value .= $ch;
        } else if (isEq($ch)) {
            $type = DfaState::EQ;
            $value .= $ch;
        } else if ($ch == '+') {
            $type = DfaState::PLUS;
            $value .= $ch;
        } else if ($ch == '-') {
            $type = DfaState::MINUS;
            $value .= $ch;
        } else if ($ch == '*') {
            $type = DfaState::STAR;
            $value .= $ch;
        } else if ($ch == '/') {
            $type = DfaState::SLASH;
            $value .= $ch;
        }
    }
}
// 当结束循环之后 需要判定一下value是否有值，如果有就继续保存一下
if ($value) {
    // 如果 value 有值就存储一下
    $tokenArr = saveToken($tokenArr, $type, $value);
}


var_dump($tokenArr);