<?php

session_start();
include_once '../../Common/PHP/all.php';

$printableVersionButton = Tag::make('button', 'Printable Version', ['name' => 'print', 'value' => 'print']);
$screenVersionButton = Tag::make('button', 'Screen Version', ['name' => 'screen', 'value' => 'screen']);

$printingVersion = $printableVersionButton->pressed() ? true : false;

list($html, $head, $body) = makePage('Sannover Flavia Air 13kw Pellet Stove', $printingVersion);

$topBar = makeTopBar();

if ($printingVersion) {
    $body->makeChild('h1', 'Sannover Flavia Air 13kw Pellet Stove');
} else {
    $topBar->addChild(makePageTitle('Sannover Flavia Air 13kw Pellet Stove'));
    $body->addChild($topBar);
}

$article = $body->makeChild("article", '', ['style' => 'margin:2ch;']);

if (!$printingVersion) {

    $article = $body->makeChild("article");

    $article->makeChild('h1', 'Configuration Table');

    $article->makeChild('p',
            "Sannover sell pellet stoves without any support claiming work should be by professionals, who mostly do not exist or are not interested in
            servicing these products. They refuse to provide any useful information whatsoever about pellet stove management to their unfortunate customers.
");

    $para = $article->makeChild('p',
            "
            They even have a video on how to reset your stove which is disasterous as in the factory reset condition the stove will not function.
");

    $para->makeChild('a', 'Danger', ['href' => 'https://www.youtube.com/watch?v=e_A1gSiz7L0&t=2s', 'class' => 'button']);

    $para = $article->makeChild('p',
            "
            I am making adjustments and have my stove working again. Once I have finished fine tuning it I will publish the optimal parameters 
            for my system. These should provide a reasonable starting point should you have been unfortunate enough to have fallen into Sannover's
            trap and done a factory reset.
");

    $article->makeChild('p',
            "
            This information is only intended for people who have the necessary technical knowledge and qualifications to be able to undertake work 
            on a pellet stove safely but who have been unable to obtain it because of the unprofessional and restrictive commercial practices of Sannover.
", ['style' => 'color:red; font-weight: bold;']);
$article->makeChild('img','',['src'=>'../Images/MicroNova.png', 'width'=>'50%']);
    $para = $article->makeChild('p',
            "
           To access the technical menu on the contro panel:
");
    
    

    $ul = $para->makeChild('ul');
    $ul->makeChild('li', 'Scroll to item 8, technical, on the user menu using the up or down buttons.');
    $ul->makeChild('li', 'Select this option using the rightmost button.');
    $ul->makeChild('li', 'You will then be expected to enter a code simply by scrolling a number up via the up button.');
    $ul->makeChild('li', 'Scroll up until A9 is displayed.');
    $ul->makeChild('li', 'Now select A9 using the rightmost button');
    $ul->makeChild('li', 'The first of the technical menus is displayed - A-1.');
    $ul->makeChild('li', 'You can scroll up and down through menus A-1 to A-6');
    $ul->makeChild('li', 'An example would be menu A-1.');
    $ul->makeChild('li', 'You can select A-1 using the rightmost button');
    $ul->makeChild('li', 'You are placed on the first option Pr01 of the manu A-1');
    $ul->makeChild('li', 'The menu option Pr01, its caption "time out mins" and its value are displayed');
    $ul->makeChild('li', 'You may now simply decrease or increase the displayed parameter using the down or up buttons or '
            . 'selct with the rightmost button which accepts the changed value and moves to the next option');
    $ul->makeChild('li', 'If you long press you will be taken back to the main menu at option A-1 (in this case)');
    $ul->makeChild('li', 'You many now move up and down to the other options');
    $ul->makeChild('li', 'One of these is A-6 which if selected escapes the menu and takes you back to the regular stove display');
    $ul->makeChild('li', 'You can also achieve this by long pressing.');
} else {
    $article = $body->makeChild("article", '', ['style' => 'margin:2ch;']);
}



/*
 * THE DATA
 */

$titleRow = ['index' => 'Prog.', 'caption' => 'Operation', 'default' => 'Default', 'mine' => 'Try', 'range' => 'Range', 'group' => 'Group', 'comment' => 'Comment'];

$A_1 = [
    ['index' => 'Pr01', 'caption' => 'timeout minutes', 'default' => "20'", 'default' => "20'", 'group'=>'Start', 'mine'=>"20'"],
    ['index' => 'Pr02', 'caption' => 'start minutes', 'default' => "03'", 'group' => 'Start','mine'=>"4'"],
    ['index' => 'Pr03', 'caption' => 'cadence cleaning', 'default' => "20'", 'group' => 'Steady'],
    ['index' => 'Pr04', 'caption' => 'lights time', 'default' => '1.4"', 'group' => 'Start',
        'comment' => 'The initial cooking of the pellets before ignition', 'mine'=>'1.0"'],
    ['index' => 'Pr05', 'caption' => 'start time', 'default' => '1.5"', 'group' => 'Start',
        'comment' => 'Gloplug ignited and then pellet loading after ignition during the starting phase. '
        . 'Again Sannover have made the default too long', 'mine'=>'1.0"'],
    ['index' => 'Pr06', 'caption' => 'auger P1', 'default' => '2.0"', 'group' => 'Steady'],
    ['index' => 'Pr07', 'caption' => 'auger P5', 'default' => '3.5"', 'group' => 'Steady'],
    ['index' => 'Pr08', 'caption' => 'cleaning speed', 'default' => '2750', 'group' => 'Steady'],
    ['index' => 'Pr09', 'caption' => 'auger cleaning', 'default' => '1.0"'],
    ['index' => 'Pr10', 'caption' => 'soglia?', 'default' => 'Off'],
    ['index' => 'Pr11', 'caption' => 'alarm delay', 'default' => '30"'],
    ['index' => 'Pr12', 'caption' => 'cleaning duration', 'default' => '35"', 'group' => 'Steady'],
    ['index' => 'Pr13', 'caption' => 'minimum threshold', 'default' => '75C'],
    ['index' => 'Pr14', 'caption' => 'maximum threshold', 'default' => '270C'],
    ['index' => 'Pr15', 'caption' => 'blower threshold', 'default' => '100C', 'group' => 'Steady'],
    ['index' => 'Pr16', 'caption' => 'smoke', 'default' => 'On'],
    ['index' => 'Pr17', 'caption' => 'smoke start', 'default' => '2100', 'group' => 'Start', 'comment' =>
        'The level of draught (extractor suck) during lighting.', 'mine'=>'2200'],
    ['index' => 'Pr18', 'caption' => 'smoke P1', 'default' => '1400', 'group' => 'Steady'],
    ['index' => 'Pr19', 'caption' => 'smoke P5', 'default' => '2100', 'group' => 'Steady'],
    ['index' => 'Pr20', 'caption' => 'blower P1', 'default' => '2000', 'group' => 'Steady'],
    ['index' => 'Pr21', 'caption' => 'blower P5', 'default' => '2250', 'group' => 'Steady'],
    ['index' => 'Pr22', 'caption' => 'encoder', 'default' => 'On'],
];

$A_2 = [
    ['index' => '38', 'caption' => 'restart halt', 'default' => "03'"],
    ['index' => '39', 'caption' => 'aspirator min spent', 'default' => "01"],
    ['index' => '40', 'caption' => 'preload igniting', 'default' => '160"', 'mine' => '80"', 'group' => 'Start', 'comment' =>
        'Crucial to starting up. This is the initial pellet load. The default amount is way too much - essentially sabotage by Sannover for the unwary.'],
    ['index' => '41', 'caption' => 'waiting fire', 'default' => '180"', 'group' => 'Start', 'comment' => '', 'mine'=>'240"'
    //'The time for the fire to start and stabilize before pellets rain down and extinguish it. The default value is too short.'
    ],
    ['index' => '42', 'caption' => 'exhaust speed preload', 'default' => '1900', 'group' => 'Start', 'mine'=>'1900'],
    ['index' => '43', 'caption' => 'difference auto', 'default' => '2.0C'],
    ['index' => '44', 'caption' => 'auto delay', 'default' => "05'"],
    ['index' => '45', 'caption' => 'power change', 'default' => '10"'],
    ['index' => '46', 'caption' => 'remote enable', 'default' => 'Off'],
    ['index' => '47', 'caption' => 'frozen keyboard', 'default' => 'Off'],
    ['index' => '48', 'caption' => 'blackout', 'default' => '30"'],
];

$A_3 = [
    ['index' => '54', 'caption' => 'load pellets', 'default' => "0", 'range' => '-9 to 9', 'group' => 'Steady'],
];

$A_4 = [
    ['index' => '55', 'caption' => 'chimney', 'default' => "0", 'range' => '-9 to 9', 'group' => 'Steady'],
];

$A_5 = [
    ['index' => '0', 'caption' => 'reset']];

$menu = ['A-1' => $A_1, 'A-2' => $A_2, 'A-3' => $A_3, 'A-4' => $A_4, 'A-5' => $A_5];

/*
 * THE VISUALISATION
 */



$article->makeChild('h4', 'All the Micronova Control Parameters');

$article->addChild(buildMasterTable(
                ['index' => 'Prog.',
                    'caption' => 'Operation',
                    'range' => 'Range',
                    'default' => 'Default',
                ],
                $menu)
);

$article->makeChild('h4', 'Parameters Crucial to the Lighting Phase');

$article->addChild(buildMasterTable(
                ['index' => 'Prog.',
                    'caption' => 'Operation',
                    //'range' => 'Range',
                    'default' => 'Default',
                //'mine' => 'Try',
                ],
                $menu, 'Start')
);

$article->makeChild('h4', 'Parameters Crucial to the Steady State');

$article->addChild(buildMasterTable(
                ['index' => 'Prog.',
                    'caption' => 'Operation',
                    'range' => 'Range',
                    'default' => 'Default',
                //'mine' => 'Try',
                ],
                $menu, 'Start')
);


$article->makeChild('h3', "I am still refining the settings but have my stove working now. The best"
        . "settings so far for start up on my setup are:");


$article->addChild(buildMasterTable(
                ['index' => 'Prog.',
                    'caption' => 'Operation',
                    'default'=>'Default',
                    'mine' => 'Try',
                ],
                $menu, 'Start')
);


$article->makeChild('h4', 'Trial and Error Card for Starting Up', ['class' => 'pageBreakBefore']);

$article->addChild(buildMasterTable(
                ['index' => 'Prog.',
                    'caption' => 'Operation',
                    'default' => 'Default',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
                $menu, 'Start')
);

$article->makeChild('h4', 'Trial and Error Card for Steady State', ['class' => 'pageBreakBefore']);

$article->addChild(buildMasterTable(
                ['index' => 'Prog.',
                    'caption' => 'Operation',
                    'range' => 'Range',
                    'default' => 'Default',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
                $menu, 'Steady')
);

if ($printingVersion) {
    $article->makeChild('form')->addChild($screenVersionButton);
} else {
    $article->makeChild('form')->addChild($printableVersionButton);
}



$html->echo();

function returnString(string $key, array $array): string {
    return isset($array[$key]) ? $array[$key] : '-';
}

function buildTableRow(array $titleRow, array $dataRow): Tag {
    $row = Tag::make('tr');

    foreach ($titleRow as $key => $caption) {
        if (array_key_exists($key, $dataRow)) {
            $entry = $dataRow[$key];
            $row->makeChild('td', $entry);
        } else {
            $row->makeChild('td');
        }
    }
    return $row;
}

// filter rows based on whether 'group' => 'Start';

function buildTable(array $titleRow, array $dataTable, ?string $filterRowKey = null): ?Tag {

    $table = Tag::make('table', '', ['class' => 'SannoverSubTable']);

    $table->addChild(buildTableRow($titleRow, $titleRow));
    $notEmpty = false;
    foreach ($dataTable as $dataRow) {
        $addRow = false;
        if ($filterRowKey) {
            if (isset($dataRow['group'])) {
                if ($dataRow['group'] === $filterRowKey) {
                    $addRow = true;
                }
            }
        } else {
            $addRow = true;
        }
        if ($addRow) {
            $table->addChild(buildTableRow($titleRow, $dataRow));
            $notEmpty = true;
        }
    }
    return $notEmpty ? $table : null;
}

function buildMasterTable(array $titleRow, array $menu, ?string $filterRowKey = null): Tag {

    $masterTable = Tag::make('table', '', ['class' => 'SannoverMasterTable']);

    foreach ($menu as $key => $tableData) {
        $subTable = buildTable($titleRow, $tableData, $filterRowKey);
        if ($subTable) {
            $tr = $masterTable->makeChild('tr');
            $tr->makeChild('td', $key, ['style' => 'width:10%']);
            $tr->makeChild('td')->addChild($subTable);
        }
    }

    return $masterTable;
}

foreach ($menu as $subTable) {
    
}
