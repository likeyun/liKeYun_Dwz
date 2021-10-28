<?php
header("Content-type:text/html;charset=utf-8");
session_start();
unset($_SESSION['lkydwz.admin']);
echo "<script>location.href=\"../index/\";</script>";
?>