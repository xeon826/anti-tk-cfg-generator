<?php

// alias _teabagnick "scripted_user_func kill,nick;"
// alias +teabagnick "bind n _teabagnick;"
// alias -teabagnick "unbind n;"
// bind alt +teabagnick

$conf = fopen('/home/dan/.steam/steam/steamapps/common/Left 4 Dead 2/left4dead2/cfg/autoexec.cfg', 'w');
$players = [['ellis', ['+use', '-use']], ['rochelle', ['+reload', '-reload']], ['coach', ['+voicerecord', '-voicerecord']], ['nick'], ['louis'], ['francis', 'impulse 100'], ['zoey', ['+mouse_menu Orders', '-mouse_menu Orders']], ['bill']];
$default_binds = ['e' => '+use', 'r' => '+reload', 'z' => '+mouse_menu Orders', 'f' => 'impulse 100', 'c' => '+voicerecord'];
$set_bindings = '';
$unset_bindings = '';
$all_infected = ['infected', 'smoker', 'jockey', 'spitter', 'tank', 'boomer', 'hunter', 'charger'];
fwrite($conf, 'alias +bene "scripted_user_func revive,all; scripted_user_func respawn,all;"'.PHP_EOL);
fwrite($conf, 'alias -bene ""'.PHP_EOL);
$alias = 'alias +kill_all_infected "';
foreach($all_infected as $key => $infected) {
    $alias .= 'scripted_user_func kill,'.$infected.';';
}
$alias .= '"'.PHP_EOL;
fwrite($conf, $alias.PHP_EOL);
fwrite($conf, 'alias -kill_all_infected ""'.PHP_EOL);
foreach ($players as $player) {
    $player_name = $player[0];
    // fwrite($conf, 'alias the_alert_'.$player_name.' "SPOTTED TEAM KILLER: '.$player_name.', COMMENCING TEA BAG!!'.PHP_EOL);
    $alias = 'teabag'.$player_name;
    $scripted_user_funcs = [
        'say team killer '.strtoupper($player_name).' spotted, commencing tea bag!!',
        ['name' => 'sound', 'args' => ['Jukebox.re_your_brains']],
        '+bene',
        // ['name' => 'defib', 'args' => ['all']],
        // ['name' => 'revive', 'args' => ['all']],
        ['name' => 'timescale', 'args' => ['0.5']],
        // ['name' => 'health', 'args' => ['all', 'perm']],
        ['name' => 'vomit', 'args' => [$player_name]],
        ['name' => 'stagger', 'args' => [$player_name, 'away']],
        ['name' => 'revivecount', 'args' => [$player_name, '2']],
        'wait 800',
        ['name' => 'warp', 'args' => [$player_name]],
        ['name' => 'incap', 'args' => [$player_name]],
        '+kill_all_infected',
        'wait 300',
        ['name' => 'kill', 'args' => [$player_name]],
        'wait 3000',
        ['name' => 'timescale', 'args' => ['1.0']],
        ['name' => 'sound', 'args' => ['stop']],
        
    ];
    $line = '';
    $alias_val = '';
    foreach ($scripted_user_funcs as $func) {
        if (gettype($func) == 'array') {
            $alias_val.='scripted_user_func '.$func['name'];
            foreach ($func['args'] as $key=>$arg) {
                $alias_val .= ','.$arg;
            }
        } else {
            $alias_val .= $func;
        }
        $alias_val.=';';
    }
    fwrite($conf, 'alias '.$alias.' "'.$alias_val.'"'.PHP_EOL);
    $set_bindings .= 'bind '.$player_name[0].' '.$alias.';';
    if (count($player) == 2 && gettype($player[1]) == 'array') {
        foreach ($player[1] as $default) {
            fwrite($conf, 'alias '.$default[0].'default_for_'.$player_name.' "'.$default.'"'.PHP_EOL);
        }
        $add_plus = '+';
    } else if (count($player) == 2) {
        fwrite($conf, 'alias default_for_'.$player_name.' "'.$default.'"'.PHP_EOL);
        $add_plus = '';
    }
    $unset_bindings .= 'bind '.$player_name[0].' '.$add_plus.'default_for_'.$player_name.';';
}
fwrite($conf, 'alias +set_bindings "'.$set_bindings.'"'.PHP_EOL);
fwrite($conf, 'alias -set_bindings "'.$unset_bindings.'"'.PHP_EOL);
fwrite($conf, 'bind alt +set_bindings'.PHP_EOL);
fclose($conf);
