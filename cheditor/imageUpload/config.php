<?php
include_once("_common.php");
// ---------------------------------------------------------------------------

# 이미지가 저장될 디렉토리의 전체 경로를 설정합니다.
# 끝에 슬래쉬(/)는 붙이지 않습니다.
# 주의: 이 경로의 접근 권한은 쓰기, 읽기가 가능하도록 설정해 주십시오.

@mkdir($_cfg['web_home'].$_cfg['data_dir']."/editor/", 0707);
@chmod($_cfg['web_home'].$_cfg['data_dir']."/editor/", 0707);

define("SAVE_DIR", $_cfg['web_home'].$_cfg['data_dir']."/editor");

# 위에서 설정한 'SAVE_DIR'의 URL을 설정합니다.
# 끝에 슬래쉬(/)는 붙이지 않습니다.


define("SAVE_URL", $_cfg['url'].$_cfg['data_dir']."/editor");

// ---------------------------------------------------------------------------
?>
