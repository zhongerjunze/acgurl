<?php

function get_curl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_URL, $url);
	$response = curl_exec($ch);
	curl_close($ch);
	//-------请求为空
	if (empty($response)) {
		return false;
	}
	return $response;
}

$APIname = "acgurl";
//此处填写API名称
$Block_IP = "off";
//开启恶意IP拦截功能则填写on,反之填写off
$FilePath = "./sinetxt.txt";
//资源文件路径

if (!file_exists("$FilePath")) {
	die("<strong>Warning:</strong>资源文件不存在或路径名称填写错误，" . $APIname . " 运行出错位置 file:" . basename(__FILE__) . " on line " . __LINE__ . ' (' . $_SERVER['SERVER_NAME'] . ')');
} else {
	$giturlArr = file($FilePath);
	//读取资源文件
}

$giturlData = [];
//将资源文件写入数组
foreach ($giturlArr as $key => $value) {
	$value = trim($value);
	if (!empty($value)) {
		$giturlData[] = trim($value);
	}
}

//随机输出一张
$randKey = rand(0, count($giturlData));
$imageUrl = $giturlData[$randKey];
//随机输出十张
$randKeys = array_rand($giturlData, 10);
$imageUrls = [];
foreach ($randKeys as $key) {
	$imageUrls[] = $giturlData[$key];
}
//json格式
$json = array(
	"server" => "$APIname",
	"code" => "200",
	"type" => "image"
);
$returnType = $_GET['return'];
switch ($returnType) {
	case 'url':
		echo $imageUrl;
		echo "<br>";
		echo "200OK-" . $_SERVER['SERVER_NAME'];
		echo "<br>";
		echo "Get Information Success from " . $APIname;
		break;

	case 'img':
		$img = file_get_contents($imageUrl, true);
		header("Content-Type: image/jpeg;");
		echo $img;
		break;

	case 'urlpro':
		foreach ($imageUrls as $imgUrl) {
			echo $imgUrl;
			echo '<br>';
		}
		echo "200OK-" . $_SERVER['SERVER_NAME'];
		echo "<br>";
		echo "Get Information Success from " . $APIname;
		break;

	case 'jsonpro':
		header('Content-type:text/json');
		$json['acgUrls'] = $imageUrls;
		echo json_encode($json);
		break;

	case 'json':
		$json['acgUrl'] = $imageUrl;
		$imageInfo = getimagesize($imageUrl);
		$json['width'] = "$imageInfo[0]";
		$json['height'] = "$imageInfo[1]";
		header('Content-type:text/json');
		echo json_encode($json);
		break;
		
	default:
		header("Location:" . $imageUrl);
		break;
}

//统计API调用次数
//@session_start();  //若访问压力大可尝试同一访客不重复记录,删去该行前//注释符即可
$Count = file_get_contents("./Main/count.txt");
//读取数据文件
if (!$_SESSION['#']) {
	$_SESSION['#'] = true;
	$Count++;
	//刷新一次+1
	$ApiTimes = fopen("./count.txt", "w");
	//以写入的方式，打开文件，并赋值给变量ApiTimes
	fwrite($ApiTimes, $Count);
	//将变量ApiTimes的值+1
	fclose($ApiTimes);
}
