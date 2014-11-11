<?php
namespace X\Service\QiNiu\Core;
class QiniuOSS {
    /**
     * 当前正在使用的bucket的名字
     * @var string
     */
    public $bucket = null;
    
    /**
     * 上传文件到七牛OSS
     * @param string $file 本地文件路径。
     * @param string $path 目标文件路径， 默认为跟目录。注意， 不是目标文件名
     * @param string $name 目标文件名， 默认与上传文件名相同。
     * @param array $config 上传所需的其他配置参数。
     */
    public function putFile( $localFile, $targetPath=null, $targetName=null, $config=array() ) {
        /* 获取上传凭证 */
        $parameters = array();
        $parameters['scope'] = $this->bucket;
        foreach ( $config as $name => $value ) {
            $parameters[$name] = $value;
        }
        if ( !isset($parameters['deadline']) ) {
            $parameters['deadline'] = 3600;
        }
        $parameters['deadline'] += time();
        $parameters = json_encode($parameters);
        $token = Qiniu_SignWithData(null, $parameters);
        
        /* 整理文件名等信息 */
        $targetName = (null === $targetName) ? basename($localFile) : $targetName;
        $targetPath = (null === $targetPath) ? $targetName : $this->convertPathToQiniuPath($targetPath.'/'.$targetName);
        
        /* 进行文件上传 */
        $client = new HttpClient(self::UPLOAD_HOST);
        $client->addParameter('token', $token);
        $client->addParameter('file', curl_file_create($localFile));
        $client->addParameter('key', $targetPath);
        $client->post();
        $result = $client->readJSON();
        
        
        
        
        $u = array('path' => self::UPLOAD_HOST);
        $req = new \Qiniu_Request($u, $parameters);
        list($resp, $err) = $this->Qiniu_Client_do($req);
        if ($err !== null) {
            return array(null, $err);
        }
        return Qiniu_Client_ret($resp);
        
        exit();
    }
    
    public static function Qiniu_Client_do($req) // => ($resp, $error)
    {
    	$ch = curl_init();
    	$url = $req->URL;
    	$options = array(
    		CURLOPT_USERAGENT => $req->UA,
    		CURLOPT_RETURNTRANSFER => true,
    		CURLOPT_SSL_VERIFYPEER => false,
    		CURLOPT_SSL_VERIFYHOST => false,
    		CURLOPT_HEADER => true,
    		CURLOPT_NOBODY => false,
    		CURLOPT_CUSTOMREQUEST  => 'POST',
    		CURLOPT_URL => $url['path']
    	);
    	$httpHeader = $req->Header;
    	if (!empty($httpHeader))
    	{
    		$header = array();
    		foreach($httpHeader as $key => $parsedUrlValue) {
    			$header[] = "$key: $parsedUrlValue";
    		}
    		$options[CURLOPT_HTTPHEADER] = $header;
    	}
    	$body = $req->Body;
    	if (!empty($body)) {
    		$options[CURLOPT_POSTFIELDS] = $body;
    	} else {
    		$options[CURLOPT_POSTFIELDS] = "";
    	}
    	curl_setopt_array($ch, $options);
    	$result = curl_exec($ch);
    	$ret = curl_errno($ch);
    	if ($ret !== 0) {
    		$err = new \Qiniu_Error(0, curl_error($ch));
    		curl_close($ch);
    		return array(null, $err);
    	}
    	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    	curl_close($ch);
    
    	$responseArray = explode("\r\n\r\n", $result);
    	$responseArraySize = sizeof($responseArray);
    	$respHeader = $responseArray[$responseArraySize-2];
    	$respBody = $responseArray[$responseArraySize-1];
    
    	list($reqid, $xLog) = getReqInfo($respHeader);
    
    	$resp = new \Qiniu_Response($code, $respBody);
    	$resp->Header['Content-Type'] = $contentType;
    	$resp->Header["X-Reqid"] = $reqid;
    	return array($resp, null);
    }
    
    /**
     * @return string
     */
    private function convertPathToQiniuPath( $path ) {
        return str_replace('/', '_', $path);
    }
    
    /**
     * @var string
     */
    const UPLOAD_HOST = 'http://upload.qiniu.com';
    
    /**
     * @var string
     */
    const SDK_VERSION = '6.1.9';
}