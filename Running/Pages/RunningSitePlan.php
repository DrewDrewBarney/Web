<?php

class RunningSitePlan {

    const MAIN_TITLE = "Drew's Resources for Runners";
    const MAIN = [
        ['caption' => 'Home', 'class' => 'Home'],
        ['caption' => 'Underlying Concepts', 'class' => 'Concepts', 'submenu' => self::SUB1],
        ['caption' => 'Tools', 'class' => 'Tools', 'submenu' => self::SUB2],
        ['caption' => 'About me', 'class' => 'About'],
        ['caption' => 'Log in / out', 'class' => 'Login'],
    ];
    const SUB1 = [
        ['caption' => 'Underlying Concepts', 'class' => 'Concepts'],
        ['caption' => 'Physiology', 'class' => 'Physiology'],
        ['caption' => 'Intensity Measures', 'class' => 'Intensity'],
        ['caption' => 'Training Load & Performance', 'class' => 'Load'],
    ];
    const SUB2 = [
        ['caption' => 'Tools', 'class' => 'Tools'],
        ['caption' => 'Interval & Repetition Paces', 'class' => 'Paces'],
        ['caption' => 'Race Predictor', 'class' => 'Predictor'],
        ['caption' => 'Critical Pace Calculator', 'class' => 'Critical'],
        ['caption' => 'Analyse Data', 'class' => 'Analyse'],
        ['caption' => 'Marathon Planner', 'class' => 'Marathon'],
        ['caption' => 'Zwift Workouts', 'class' => 'Zwift'],
       
    ];
}
