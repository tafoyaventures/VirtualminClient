<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    function getnexttag()
    {
        $cmd = 'git tag --sort=taggerdate | tail -1';
        exec($cmd, $out);
        $lastTag = str_replace("v","", $out[0]);
        $exp = explode(".", $lastTag);
        $nextTag = "v" . (int)$exp[0] . "." . (int)$exp[1] . "." . ((int)$exp[2] + 1);
        return $nextTag;
    }

    function push($message)
    {
        $this->taskGitStack()
            ->stopOnFail()
            ->push('origin','master')
            ->tag($this->getnexttag())
            ->push('origin',$this->getnexttag())
            ->run();
    }
}