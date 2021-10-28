<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header("Content-type:application/json");

$dburl = trim($_POST["dburl"]);
$dbuser = trim($_POST["dbuser"]);
$dbpwd = trim($_POST["dbpwd"]);
$dbname = trim($_POST["dbname"]);
$user = trim($_POST["user"]);
$pwd = trim($_POST["pwd"]);

if (empty($dburl) || empty($dbuser) || empty($dbpwd) || empty($dbname) || empty($user) || empty($pwd)) {
  $result = array(
    "code" => "101",
    "msg" => "请把所有输入框填完再安装"
  );
}else{
  // 创建连接
  $conn = mysqli_connect($dburl, $dbuser, $dbpwd, $dbname);
  // 检测连接
  if (!$conn) {
    $error_msg = mysqli_connect_error();
    if(strpos($error_msg,'database') !== false){ 
      // 包含database则为数据库名错误
      $result = array(
        "code" => "102",
        "msg" => "数据库名称错误"
      );
    }else if(strpos($error_msg,'password') !== false){
      // 包含password则为账号密码错误
      $result = array(
        "code" => "103",
        "msg" => "数据库账号或密码错误"
      );
    }else{
      $result = array(
        "code" => "104",
        "msg" => "数据库地址错误"
      );
    }
  }else{

    // 检查是否已经安装
    $check_db_config = "../dbconfig/db.php";
    if(file_exists($check_db_config)){
      $result = array(
        "code" => "108",
        "msg" => "请勿重复安装，如需重新安装请把dbconfig/db.php删掉。"
      );
      echo json_encode($result,JSON_UNESCAPED_UNICODE);
      exit;
    }

    // 短网址列表：dwz_list
    $dwz_list = "CREATE TABLE dwz_list (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL, 
    dwz_id VARCHAR(32),
    dwz_key VARCHAR(32),
    dwz_creat_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dwz_url TEXT(300),
    dwz_title VARCHAR(32),
    dwz_type VARCHAR(32),
    dwz_yxq VARCHAR(32),
    dwz_reditype VARCHAR(32),
    dwz_pv VARCHAR(32) DEFAULT '0',
    dwz_status VARCHAR(32) DEFAULT '1',
    dwz_rkym TEXT(300),
    dwz_ffym TEXT(300))";

    // 短网址API：dwz_api
    $dwz_api = "CREATE TABLE dwz_api (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL, 
    api_id VARCHAR(32),
    api_user VARCHAR(32),
    api_key VARCHAR(32),
    api_yxq VARCHAR(32),
    api_creat_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    api_status VARCHAR(32) DEFAULT '1',
    api_ip VARCHAR(32))";

    // 短网址API统计：dwz_tongji
    $dwz_tongji = "CREATE TABLE dwz_tongji (dwz_api_qq_num VARCHAR(32) DEFAULT '0')";

    // 短网址域名（入口域名和防封域名）：dwz_ym
    $dwz_ym = "CREATE TABLE dwz_ym (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    ym_id VARCHAR(32),
    ym TEXT(300),
    ym_type VARCHAR(200))";

    // 判断安装结果
    if ($conn->query($dwz_list) === TRUE
      && $conn->query($dwz_api) === TRUE
      && $conn->query($dwz_tongji) === TRUE
      && $conn->query($dwz_ym) === TRUE) {

      // 创建数据库配置文件
	  $db_config_file = '<?php' . PHP_EOL . '  /**' . PHP_EOL . '   *  数据库配置' . PHP_EOL . '   *  Author：TANKING' . PHP_EOL . '   *  Date：'.date("Y-m-d").'' . PHP_EOL . '   *  Web：www.likeyunba.com' . PHP_EOL . '   **/' . PHP_EOL . '  $db_url = "'.$dburl.'";' . PHP_EOL . '  $db_user = "'.$dbuser.'";' . PHP_EOL . '  $db_pwd = "'.$dbpwd.'";' . PHP_EOL . '  $db_name = "'.$dbname.'";' . PHP_EOL . '  $admin_user = "'.$user.'";' . PHP_EOL . '  $admin_pwd = "'.$pwd.'";' . PHP_EOL . '?>';

	  file_put_contents('../dbconfig/db.php', $db_config_file);

	  // 初始化数据
	  $dwz_api_qq_num = "INSERT INTO dwz_tongji (dwz_api_qq_num) VALUES ('0')";
	  mysqli_query($conn,$dwz_api_qq_num);

	  $result = array(
          "code" => "100",
          "msg" => "安装成功"
      );

      // 断开数据库连接
      mysqli_close($conn);

    }else{
      if(strpos($conn->error,'already exists') !== false){
        $result = array(
          "code" => "105",
          "msg" => "请勿重复安装，如需重新安装请把数据库中所有dwz_前缀的表删掉。"
        );
      }else{
        $result = array(
          "code" => "106",
          "msg" => "安装失败，失败原因：".$conn->error
        );
      }
    }
  }
}

// 返回JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>
