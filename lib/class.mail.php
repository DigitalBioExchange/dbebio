<?
class yskEmailClass {

    var $_from;
    var $_to;
    var $_name;
    var $_title;
    var $_content;
    var $_header;
    var $_bound;
    var $_file = array();

    function init($arr) {
        $this->_from    = $arr['from'];
        $this->_to        = $arr['to'];
        $this->_name    = $arr['name'];
        $this->_title    = $arr['title'];
        $this->_content    = $arr['content'];
        $this->_file    = $arr['file'];
        $this->_bound    = '-------'.$_SERVER["SERVER_NAME"].'='.md5(microtime());
        return $this->_header();
    }

    function _header() {
        $this->_header    = 'Return-Path: '.$this->_to."\n";
        $this->_header .= "From: =?UTF-8?B?".base64_encode($this->_name)."?="."<" . $this->_to . ">\n";
        if($this->_file[0]['file']) {
            $this->_header .= 'Content-Type: multipart/mixed; boundary="'.$this->_bound.'"'."\n";
            $this->_fileContentHeader();
        } else {
            $this->_header .= 'Content-Type: text/html; charset=UTF-8'."\n";
        }
        $this->_header .= 'MIME-Version: 1.0'."\n";
        return $this->_send();
    }

    function _fileContentHeader() {
        $x = 1;
        $count = custom_count($this->_file);
        foreach($this->_file as $v) {
            $file_name = basename($v['name']);
            $fp = @fopen($v['file'], 'r');
            $file_content = @fread($fp, filesize($v['file']));
            @fclose($fp);
            $file_content = base64_encode($file_content);

            if($v['type'] == '') {
                $file_type = 'application/octet-stream';
            } else {
                $file_type = $v['type'];
            }

            $file_tmp .= 'Content-Type: '.$file_type.'; name="'.$file_name.'"'."\n";
            $file_tmp .= 'Content-Transfer-Encoding: base64'."\n\n";
            $file_tmp .= $file_content."\n";
            if($x < $count) {
                $file_tmp .= '--'.$this->_bound."\n";
            }
            $x++;
        }

        $tmp  = '--'.$this->_bound."\n";
        $tmp .= 'Content-Type: text/html; charset=UTF-8'."\n";
        $tmp .= 'Content-Transfer-Encoding: 8bit'."\n\n";
        $tmp .= $this->_content."\n\n";
        $tmp .= '--'.$this->_bound."\n";
        $tmp .= $file_tmp;
        $this->_content = $tmp;
    }

    function _send() {
        $result = mail($this->_from, "=?utf-8?B?".base64_encode($this->_title)."?=\n", $this->_content, $this->_header);
        return $result;
    }
}


/* ?????????
------------------?????????------------------

## ???????????? ????????????
$arr_file = array();
for($x=0; $x<1; $x++) { //$x ??? ????????? ?????? ????????? ?????????
  $arr_file[$x]['file'] = '?????????????????????????????? ?????? ??????????????????';
  $arr_file[$x]['name'] = '????????????';
  switch($ext) { //$ext ??????????????? ????????????~
    case 'gif':
        $arr_file[$x]['type'] = 'image/gif';
                              break;
    case 'jpg':
    case 'jpeg':
        $arr_file[$x]['type'] = 'image/jpeg';
                    break;
    case 'png':
        $arr_file[$x]['type'] = 'image/png';
        break;
    case 'bmp':
        $arr_file[$x]['type'] = 'image/bmp';
        break;
    default:
        $arr_file[$x]['type'] = '';
        break;
  }
}

$arr_email            = array();
$arr_email['from']        = '???????????????????????????';
$arr_email['to']        = '??????????????????????????????';
$arr_email['name']        = '?????????????????????';
$arr_email['title']        = '??????';
$arr_email['content']                  = '??????';
$arr_email['file'] = $arr_file;

$cls = new yskEmailClass;
$result = $cls->init($arr_email);
if($result == true) {
  echo 1;
} else {
  echo 0;
}
*/

?>