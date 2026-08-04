<?php

namespace App\Services;

use App\Models\Order;

/**
 * 決済ゲートウェイ連携のダミー実装。
 *
 * 現状は決済代行会社と連携していないため常に即時承認するだけだが、
 * Stripe等の実決済を導入する際はこのクラスを差し替えるか、
 * charge() をWebhookからの非同期確定処理に置き換える想定。
 */
class PaymentService
{
    /**
     * 注文の決済を実行し、承認されれば true を返す
     */
    public function charge(Order $order): bool
    {
        return true;
    }
}
