<?php
if(isset($success_msg)){
  foreach ($success_msg as $success_msg) {
    echo "<script>createToast('success', '".$success_msg."')</script>";
  }
}
if(isset($warning_msg)){
  foreach ($warning_msg as $warning_msg) {
    echo "<script>createToast('warning', '".$warning_msg."')</script>";
  }
}
if(isset($error_msg)){
  foreach ($error_msg as $error_msg) {
    echo "<script>createToast('error', '".$error_msg."')</script>";
    
  }
}
if(isset($info_msg)){
  foreach ($info_msg as $info_msg) {
    echo "<script>createToast('info', '".$info_msg."')</script>";
  }
}