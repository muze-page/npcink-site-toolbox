<?php
$RunCode = new RunCode();
add_filter('the_content', array($RunCode, 'part_one'), -500);
add_filter('the_content', array($RunCode, 'part_two'), 500);
unset($RunCode);

class RunCode
{
    public $blocks = array();

    public function part_one($content)
    {
        $str_pattern = "/(\<runcode(.*?)\>(.*?)\<\/runcode\>)/is";
        if (preg_match_all($str_pattern, $content, $matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $code = htmlspecialchars($matches[3][$i]);
                $code = preg_replace("/(\s*?\r?\n\s*?)+/", "\n", $code);
                $num = rand(1000, 9999);
                $id = "runcode_$num";
                $blockID = "<p>++RUNCODE_BLOCK_$num++";
                $innertext = '
                    <textarea readonly id="' . $id . '" class="runcode" style="height: auto; min-height: 150px; max-height: 300px; overflow-y: auto;">' . $code . '</textarea>
                    <input class="runcode2" type="button" value="运行代码" onclick="runCode(\'' . $id . '\')"/>
                    <input class="btn btn--secondary" style="margin-left: 30px;" type="button" value="全选代码" onclick="selectCode(\'' . $id . '\')"/>
                    <input class="btn btn--secondary" style="margin-left: 30px;" type="button" value="复制代码" onclick="copyCode(\'' . $id . '\')"/>
                ';
                $this->blocks[$blockID] = $innertext;
                $content = str_replace($matches[0][$i], $blockID, $content);
            }
        }
        return $content;
    }

    public function part_two($content)
    {
        if (count($this->blocks)) {
            $content = str_replace(array_keys($this->blocks), array_values($this->blocks), $content);
            $this->blocks = array();
        }
        return $content;
    }
}

add_action('after_wp_tiny_mce', 'bolo_after_wp_tiny_mce');
function bolo_after_wp_tiny_mce($mce_settings) {
?>
<script type="text/javascript">
QTags.addButton( 'shipindai', '代码运行', '\n<runcode>\n','\n</runcode>\n' );
function bolo_QTnextpage_arg1() {
}
</script>
<?php
}
