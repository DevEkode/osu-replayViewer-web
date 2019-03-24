<?php
class ini {
    protected $lines;
    protected $file;

    public function read($file) {
        $this->lines = array();
        $this->file = $file;

        $section = '';

        foreach(file($file) as $line) {
            // comment or whitespace
            if(preg_match('/^\s*(;.*)?$/', $line)) {
                $this->lines[] = array('type' => 'comment', 'data' => $line);
            // section
            } elseif(preg_match('/\[(.*)\]/', $line, $match)) {
                $section = $match[1];
                $this->lines[] = array('type' => 'section', 'data' => $line, 'section' => $section);
            // entry
            } elseif(preg_match('/^\s*(.*?)\s*=\s*(.*?)\s*$/', $line, $match)) {
                $this->lines[] = array('type' => 'entry', 'data' => $line, 'section' => $section, 'key' => $match[1], 'value' => $match[2]);
            }
        }
    }

    public function get($section, $key) {
        foreach($this->lines as $line) {
            if($line['type'] != 'entry') continue;
            if($line['section'] != $section) continue;
            if($line['key'] != $key) continue;
            return $line['value'];
        }

        throw new Exception('Missing key or section');
    }

    public function set($section, $key, $value) {
        foreach($this->lines as &$line) {
            if($line['type'] != 'entry') continue;
            if($line['section'] != $section) continue;
            if($line['key'] != $key) continue;
            $line['value'] = $value;
            $line['data'] = $key . " = " . $value . "\r\n";
            return;
        }
        
        throw new Exception('Missing key or section');
    }

    public function repairKey($section,$key,$defaultData){
        //Create new line if it doesn't exists

        //Add new key after section beginning
        $file2 = new SplFileObject($this->file,'r');

        $output = '';
        $value = $defaultData[$section][$key];
        while (!$file2->eof()) {
            $line2 = $file2->fgets();
            $compareTo = '['.$section."]";
            $line2_clean = preg_replace('/[[:^print:]]/', "", $line2);

            if($line2_clean == $compareTo){
               $line2 .= $key.' = '.$value."\r\n";
            }
            $output .= $line2;
        }
        $file2 = null;

        unlink($this->file);

        $explode = explode("\n",$output);
        $array = array();
        foreach($explode as &$line) {
            $newLine = array('data' => $line);
            $array[] = $newLine;
        }

        $this->write2($this->file,$array);
    }

    public function exists($section, $key){
        try {
            $this->get($section,$key);
        } catch (Exception $e){
            return false;
        }
        return true;
    }

    public function writeArray($file,$section,$data) {
        $fp = fopen($file, 'a');
        fwrite($fp,'['.$section."]"."\r\n");

        foreach ($data as $key => $value) {
            fwrite($fp, $key.' = '.$value."\r\n");
        }

        fclose($fp);
    }

    public function write($file) {
        $fp = fopen($file, 'w');

        foreach($this->lines as $line) {
            fwrite($fp, $line['data']);
        }

        fclose($fp);
    }

    public function write2($file,$newLines) {
        $fp = fopen($file, 'w');

        foreach($newLines as $line) {
            fwrite($fp, $line['data']."\n");
        }

        fclose($fp);
    }
}
?>
