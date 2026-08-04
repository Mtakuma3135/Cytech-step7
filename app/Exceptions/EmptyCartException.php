<?php

namespace App\Exceptions;

use Exception;

/**
 * カートが空の状態でチェックアウトが実行された場合に投げる。
 */
class EmptyCartException extends Exception
{
}
