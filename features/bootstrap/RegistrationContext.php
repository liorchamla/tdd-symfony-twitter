<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class RegistrationContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given an anonymous user
     */
    public function anAnonymousUser()
    {
        throw new PendingException();
    }

    /**
     * @When he browses :arg1
     */
    public function heBrowses($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then he sould see a form
     */
    public function heSouldSeeAForm()
    {
        throw new PendingException();
    }

    /**
     * @When he fills in the form with email :arg1, username :arg2 and password :arg3
     */
    public function heFillsInTheFormWithEmailUsernameAndPassword($arg1, $arg2, $arg3)
    {
        throw new PendingException();
    }

    /**
     * @Then he should be redirected to :arg1
     */
    public function heShouldBeRedirectedTo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then we should find his data in the database
     */
    public function weShouldFindHisDataInTheDatabase()
    {
        throw new PendingException();
    }

    /**
     * @Then his password should have been hashed
     */
    public function hisPasswordShouldHaveBeenHashed()
    {
        throw new PendingException();
    }
}
