<?php
class CheckShell extends AppShell {

    public function main() {

        //shell_exec('less');
        if (!defined('T_ML_COMMENT')) {
            define('T_ML_COMMENT', T_COMMENT);
        } else {
            define('T_DOC_COMMENT', T_ML_COMMENT);
        }

            $this->tokenize();
    }

    public function tokenize()
    {

$filename = null;
        $controllerPaths = glob(APP.'Controller/*.php');
        $this->out("<refactor>List of Controllers</refactor>");
        $controllerIndex=1;
        $controllerIndex=$this->getFiles($controllerPaths,$controllerIndex);

        $modelPaths = glob(APP.'Model/*.php');
        $this->out("<refactor>List of Models</refactor>");
        $modelIndex=$controllerIndex;

        $modelIndex=$this->getFiles($modelPaths,$modelIndex);


        $this->out("[q] Quit");
        $option = $this->in("Which file do you want to check?");
        if (in_array($option, range(1, $controllerIndex - 1))) {
            $file = $controllerPaths[$option - 1];

        }
        elseif (in_array($option, range($controllerIndex-1, $modelIndex - 1))) {
            $file = $modelPaths[$option - ($controllerIndex)];

        }
        elseif($option == 'q')
        {
            exit();
        }
        else
        {

            $this->out("\nchoose valid option\n");
            $this->tokenize();
            return;
        }

        $source = file_get_contents($file);
        $tokens = token_get_all($source);
        $this->out(__d('cake_console',"\nYour code is being checked...\n"));
        $this->hr();

        $tok = strtok($file,"/");

        while ($tok !== false) {

            $tok = strtok("/");
            if(strpos($tok, "."))
            {
                $name = $tok;
            }
        }

        $this->out("<refactor>File being checked is $name </refactor>");
        $this->hr();

        foreach ($tokens as $token) {
            if (is_string($token)) {

            } else {
                // token array
                list($id, $text) = $token;

                switch ($id) {
                    case T_COMMENT:
                        $subStr = '@refactor';
                        $subStr2 = '@todo';
                        $subStr3 = '@comment';
                        $pos = strpos($text, $subStr);
                        $pos2 = strpos($text, $subStr2);
                        $pos3 = strpos($text, $subStr3);

                        if($pos != false || $pos2 != false || $pos3 != false)
                        {
                            if($pos != false)
                            {
                                $this->out("<refactor>REFACTOR</refactor>");
                              //  $refactor_arr[$refac++]= $text;
                            }
                            elseif($pos2 != false)
                            {
                                $this->out("<todo>TO DO </todo>");
                              //  $todo_arr[$todo_index++]= $text;
                            }
                            elseif($pos3 != false)
                            {
                                $this->out("<comment>COMMENT</comment>");
                               // $comment_arr[$comment_index++]= $text;
                            }

                            echo "$text\n";
                            $this->hr();

                        }

                    default:

                        break;
                }
            }
        }

    }

    public function startup()
    {
        $this->clear();
        $this->stdout->styles('refactor', array('text' => 'red', 'blink' => true));
        $this->stdout->styles('todo', array('text' => 'blue', 'blink' => true));
        $this->stdout->styles('comment', array('text' => 'yellow', 'blink' => true));
        $this->hr();
        $this->out("<comment>Welcome to code checker..</comment>");
        $this->hr();

    }

    public function getFiles($paths,$index)
    {
        foreach($paths as $path)
        {
            $tok = strtok($path,"/");

            while ($tok !== false) {

                $tok = strtok("/");
                if(strpos($tok, "."))
                {
                    $this->out('['.$index++.']  '.$tok);

                }
            }

        }
        return $index;

    }
}
?>
