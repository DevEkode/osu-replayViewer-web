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

        throw new Exception('Missing Section or Key');
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
        
        //Create new line if it doesn't exists
        $section_line = $this->getLineWithString(file($this->file),'['.$section.']');

        //Add new key after section beginning
        $file2 = new SplFileObject($this->file,'w+');
        $file2->seek($section_line); // Seek to line no. 10,000
        $file2->fwrite($key.' = '.$value);
        $file2 = null;
        var_dump($key.' = '.$value);
        //throw new Exception('Missing Section or Key');
    }

    private function getLineWithString($lines, $str) {
        foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $str) !== false) {
                return $lineNumber;
            }
        }
        return -1;
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
        fwrite($fp,'['.$section."]\n");

        foreach ($data as $key => $value) {
            fwrite($fp, $key.' = '.$value."\n");
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
}
?>
