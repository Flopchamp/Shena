<?php
/**
 * QrService - simple QR helper to return a QR image URL or data
 */
class QrService
{
    /**
     * Return an external QR image URL for a paybill/account combination.
     * Uses api.qrserver.com to generate a QR image URL.
     * @param string $paybill
     * @param string $account
     * @param int $size
     * @return string
     */
    public static function paybillQrUrl($paybill, $account, $size = 200)
    {
        $data = urlencode("MPESA PAYBILL:{$paybill}|ACC:{$account}");
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$data}";
    }

    /**
     * Return a data URI for a QR (optional, not used currently).
     */
    public static function paybillQrDataUri($paybill, $account, $size = 200)
    {
        $url = self::paybillQrUrl($paybill, $account, $size);
        $img = @file_get_contents($url);
        if ($img === false) return '';
        $base64 = base64_encode($img);
        return "data:image/png;base64,{$base64}";
    }
}
