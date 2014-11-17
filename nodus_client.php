class nodus_clinet
{
    const IV = '';      // Fill with IV
    const KEY = '';	// Fill with our key 
    const BUSINESS_CODE = '';	// Fill with Business code
    const EPAY_HOST = '';	// URL for E-Pa host
    function encryptNET3DES($text,$key,$iv)
    {
        if (!isset($key) || $key)
            $key= self::KEY;
        if (!isset($iv) || $iv)
            $iv = self::IV;
       $td = mcrypt_module_open (MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');

       // Complete the key
       $key_add = 24-strlen($key);
       $key .= substr($key,0,$key_add);

       // Padding the text
       $block = mcrypt_get_block_size('tripledes', 'cbc');
       $len = strlen($text);
       $padding = $block - ($len % $block);
       $text .= str_repeat(chr($padding),$padding);

       mcrypt_generic_init ($td, $key, $iv);
       $encrypt_text = mcrypt_generic ($td, $text);
       mcrypt_generic_deinit($td);
       mcrypt_module_close($td);
       return base64_encode($encrypt_text);
    }

    function decryptNET3DES($text,$key,$iv)
    {

        if (!isset($key) || $key)
            $key= self::KEY;
        if (!isset($iv) || $iv)
            $iv = self::IV;
        $text = base64_decode($text);
       $td = mcrypt_module_open (MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');

       // Complete the key
       $key_add = 24-strlen($key);
       $key .= substr($key,0,$key_add);

       mcrypt_generic_init ($td, $key, $iv);
       $decrypt_text = mdecrypt_generic ($td, $text);
       mcrypt_generic_deinit($td);
       mcrypt_module_close($td);

       //remove the padding text
       $block = mcrypt_get_block_size('tripledes', 'cbc');
       $packing = ord($decrypt_text{strlen($decrypt_text) - 1});
       if($packing and ($packing < $block))
       {
           for($P = strlen($decrypt_text) - 1; $P >= strlen($decrypt_text) - $packing; $P--)
           {
                if(ord($decrypt_text{$P}) != $packing)
                {
                    $packing = 0;
                }
          }
       }
       $decrypt_text = substr($decrypt_text,0,strlen($decrypt_text) - $packing);
       return $decrypt_text;
    }

    function get_url_client($gp_id){
        $gp_id = $this->encryptNET3DES($gp_id,null,null);
        $url = sprintf('/sso.aspx?a1=%s&var3=%s&var4=cus',urlencode($gp_id),self::BUSINESS_CODE);
        return $url;
    }
    function client_url($gp_id)
    {
        $url = $this->get_url_client($gp_id);
        $url = self::EPAY_HOST.$url;
        return $url;
    }
}
?>
