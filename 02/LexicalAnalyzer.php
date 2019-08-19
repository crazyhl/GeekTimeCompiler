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

$str = 'age >= 45';

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
            $lastType = $type;
            $type = DfaState::Initial;
            break;
    }
    if ($type == DfaState::Initial) {
        if ($value) {
            // 如果 value 有值就存储一下
            $token = new Token();
            $token->type = $lastType;
            $token->value = $value;
            $tokenArr[] = $token;

            $value = '';
        }


        if (isAplha($ch)) {
            $type = DfaState::ID;
            $value .= $ch;
        } else if (isNumber($ch)) {
            $type = DfaState::IntLiteral;
            $value .= $ch;
        } else if (isGt($ch)) {
            $type = DfaState::GT;
            $value .= $ch;
        }
    }
}

if ($value) {
    // 如果 value 有值就存储一下
    $token = new Token();
    $token->type = $type;
    $token->value = $value;
    $tokenArr[] = $token;

    $value = '';
}


var_dump($tokenArr);