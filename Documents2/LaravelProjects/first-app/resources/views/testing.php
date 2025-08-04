<?php

include_once 'PHP/all.php';

list($html, $head, $body) = makePage('Field Test');

$serialWorkout = file_get_contents('workout');
    
if (!$serialWorkout) {
    popup('new');
    
    // field objects
    $fieldPool = new FieldPool();
    $field = new LabeledTextField('input 1', '', new CheckInteger(0, 10));
    $field2 = new LabeledTextField('input 2');
    $field3 = new SelectField(['one', 'two', 'three'], 'two');
    $submit1 = new ButtonField('submit 1', 'target_1');
    $submit2 = new ButtonField('submit 2', 'target_2', [$submit1, 'testCallback']);

    // tag objects forming the DOM
    $form = Tag::make('form');
    $form->addChild($field->makeTags());
    $form->addChild($field2->makeTags());
    $form->addChild($field3->makeTags());
    $form->addChild($submit1->makeTags());
    $form->addChild($submit2->makeTags());

    $serialWorkout = serialize($form);
    file_put_contents('workout', $serialWorkout);
     
} else {
    $form = unserialize($serialWorkout);
}

$body->addChild($form);
//ButtonPool::triggerButtons();

$html->echo();

echo '<pre>';
print_r($_GET);

