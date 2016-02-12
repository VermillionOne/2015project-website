<?php

class PagesControllerTest extends TestCase {
  ///////////////////////////////////////////////
  /// TESTS BELOW THIS POINT ARE NOT COMPLETE ///
  ///////////////////////////////////////////////
  public function testGetRobots ()
  {
    // Get robots
    $result = $this->call('GET', 'robots.txt');

    // Check response
    $this->assertResponseOk();

    // Check content is equal
    $this->assertEquals('User-agent: *' . "\n" . 'Disallow:  / ', $result->getContent());
  }

  public function testGetSellTickets ()
  {
    // Get home page
    $result = $this->call('GET', 'sellTickets');

    // Set data
    $data = $result->original;

    // Check response
    $this->assertResponseOk();

    // Check true status
    $this->assertTrue(isset($data['metaTitle']));
    $this->assertTrue(isset($data['eventsTagsIsFeatured']));
    $this->assertTrue(isset($data['eventsIsFeatured']));
  }

  public function testGetHome ()
  {
    $result = $this->call('GET', '/');

    // Set data
    $data = $result->original;

    // Check response
    $this->assertResponseOk();

    // Check true status
    $this->assertTrue(isset($data['metaTitle']));
    $this->assertTrue(isset($data['eventsIsFeatured']));
    $this->assertTrue(isset($data['eventsTagsIsFeatured']));
  }

  /**
   * Get mobile
   * No data passed, nothing to check but response
   *
   * @return void
   */
  public function testGetMobile ()
  {
    $result = $this->call('GET', 'mobile');

    // Check response
    $this->assertResponseOk();
  }

  /**
   * Get terms and conditions
   * No data passed, nothing to check but response
   *
   * @return void
   */
  public function testGetTerms ()
  {
    $result = $this->call('GET', 'terms-conditions');

    // Check response
    $this->assertResponseOk();
  }

  /**
   * Get Privacy
   * No data passed, nothing to check but response
   *
   * @return void
   */
  public function testGetPrivacy ()
  {
    $result = $this->call('GET', 'privacy');

    // Check response
    $this->assertResponseOk();
  }

  /**
   * Get pages loader (Logged out)
   *
   * @return void
   */
  public function testGetPagesLoader() {

    // Get loader
    $result = $this->call('GET', 'events/loader');

    // Check status
    $this->assertResponseStatus(302);

    // Check if logged out is redirected
    $this->assertRedirectedToRoute('register');
  }
}
