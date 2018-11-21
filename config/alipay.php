<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12/012
 * Time: 9:20
 */

return [
    //应用ID
    'app_id' =>env('ALIPAY_APPID','2018091461341618'),

    //商户私钥
    'private_key'=> env('MERCHANT_PRIVATE_KEY','MIIEpQIBAAKCAQEApam4czk4nC/d73XU0rIfYdl1IKB9qiy2RWpRQwZI0njZDikWwDM0mk1F1Z7xwlqPJUm7l4d4LknU1Z9jmsCkTH52tSF4FCf8lw04AmNo9Ow2FOHnTydnoo7+ml/5hyQs8Ktd4yZtcuSkamubh07abq4duPDykmAXOzShPnYItSao+dfUpPAYBVVPVkdot4gUWNd5e+siCZO3iMnBAIpp0sWHXUtwEkCdpe+C2RE7P2u9TNu9KNNibGwniwZpmo/1M9qwrhyWpHcwYe5wkMlpAfIP8lvQUg1/uVpdUb0Wrq7srxBDXAB8ECIjv/rLKSg/Z0uRweHns2dVVuBplJSG8QIDAQABAoIBAQCT07RdVXVXojsoAHyOGxZ36WVkXPJmFYn8vVeQOJ5o+h+uTCoaDldlTYkAi9nt9YOA4Z+9IsyyeX2rtpRaNocmHz9seFY/nkL+w3P0ZaL104aa8c7HykDnRTSaqwPufPCBPloEZXkLKk4xDJ73ifzPAR5StueuiIKSeW9dXlR82Hgr1kpKMO8kZ+0PIisC7m2vAYw1xa0rlXtnb2zuSzUnW7akgV2/yFEFlk4FVG+2SGRYWT4N6VvtZpX8dyIhT6zx4hoYTnbSSQlLGES4r52X3PEcMS5CrcBHF2zDvO7b4zgVkFimxH19+dQS7zMPH9zc0XsasAmePS/IYFGfqFhlAoGBAM9PxWu7nM5XEYTlgDy2eCz4i9t8+8pPY//KBABkTX0kBUq6cl3ditvcXeJslCyzWX6QoCmgqmTrduebY5CSJaMyKSUSj23m+QekKvuIbBKiSUXj0H+9xNgEyUcyYGv9Rnn8IQNAo6j+MixusaFOAnuUm4YViz69e8A08eMBiMX3AoGBAMyR5tptKCeqh8+BSfY/hcl0QFgcuEmIybaqEVQBiXDskyYRt3JN5dTY7rK8hkeu8j9K2CqGIsrckn8KsEDgzMTbO5JzxUzFvZl94D6M9jB64o4E2eXBhm4wr/mAc5/Ef3Xr9423BEsBwtn4zEqPESxb3jKkEH8KYsgRCd8yYMBXAoGBAKIfzFB4JCTpynWX1TWlrU3eAeHgWDja5WVAYui7iC35YXoTXSUzAEj4iSNtrmNWzwI/sEFduGO8EP0HdFG3OV/q9mpbnXdhThc/t62F/p6D87GIe6Gqkqos9AuNxgItzFPRbS/R+StLv4kn+Bohm6dxjD7L0l16abDittfEd2X5AoGBAKdOYE6d3axrRL2J0NKrayCyiflGK9ZAIDDbcGaTBzQq18HX1X+Pg/uLG9VkaDHyO79V1+sXXxcoREvQkwvMjswlPNhvBJrgUktZbm/Z23MU3XsnLopAt1AhU5qr2mR9UX1A6mqllsY9f33DhFkqHvJ3hAdItREhjl5vyPIZApXrAoGAFBh4uFKwIL7hv4IKgVr/Fts9flXqpsmslbVojinPiSlb65I256Ei7pLIm4WKVYMOnoxRt5tTJJwR0LrF3jrq+DBXRJx7awwU11z/j0ZapuqHtd+VFYe6jALtyOUuCG2kfU7dIVe3gpfSm9EB9UM5FrWDU3g4hxHCWc1paqlQaKA='),

    //应用公钥
    'public_key' =>env('ALIPAY_PUBLIC_KEY','MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApam4czk4nC/d73XU0rIfYdl1IKB9qiy2RWpRQwZI0njZDikWwDM0mk1F1Z7xwlqPJUm7l4d4LknU1Z9jmsCkTH52tSF4FCf8lw04AmNo9Ow2FOHnTydnoo7+ml/5hyQs8Ktd4yZtcuSkamubh07abq4duPDykmAXOzShPnYItSao+dfUpPAYBVVPVkdot4gUWNd5e+siCZO3iMnBAIpp0sWHXUtwEkCdpe+C2RE7P2u9TNu9KNNibGwniwZpmo/1M9qwrhyWpHcwYe5wkMlpAfIP8lvQUg1/uVpdUb0Wrq7srxBDXAB8ECIjv/rLKSg/Z0uRweHns2dVVuBplJSG8QIDAQAB'),

    //支付宝公钥
    'alipay_key' =>env('ALIPAY_ALIPAY_KEY','MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkar/C/19qRlues+N8q27f4Nwjiom1PK3jfC8GwKbN/ximTM9qx3Ck1g+CCLVANWBO4bFf7t2UMG9xdx+R9R+xV4+aNv6seRdCQ6HMSK4csl/p6Hr8lvknGX+VU4Yq89T1fR7dEK5DGlNPpI0onOuvGwHOK3AZyIQ0VxMrDTH2Wm8kQXIH4mY9HNsz4qz7YNC1NeSp7054CS2C7HbghdX8KNtdtJGML2mqUmA4eUJZ0DCcJuXdk86lpX/KguYIHaAOZQ8q66AzVD2eWDND+DC4QobcrMfH6xCA9TKe/5/p2ywodaL2O7JzQRqttH1vfg2I0Z39q0U0bmokbgOXYm7EwIDAQAB'),

    //默认异步通知地址
    'notify_url' =>env('ALIPAY_WEB_NOTIFY_URL',''),

    //默认同步跳转地址

    'return_url' => env('ALIPAY_WEB_RETURN_URL',''),

    //编码格式
    'charset' => 'UTF-8',

    //签名方式
    'sign_type'=>'RSA2',

    //支付宝网关
    'gatewayUrl' => env('ALIPAY_GATEWAY_URL','https://openapi.alipay.com/gateway.do')
];