<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('see main page');
$I->amOnPage('/');
$I->see("Congratulations", "h1");
