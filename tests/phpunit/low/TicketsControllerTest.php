<?php

class TicketsControllerTest extends TestCase {
  ///////////////////////////////////////////////
  /// TESTS BELOW THIS POINT ARE NOT COMPLETE ///
  ///////////////////////////////////////////////
  /**
   * Get dashboard (Logged Out)
   */
  public function GetDashboard()
  {
    $response = $this->call('GET', 'account/dashboard');

    $this->assertRedirectedToRoute('register');
  }

  /**
   * Ticketes index from Dashboard (Logged Out)
   * @return void
   */
  public function testGetAccountDashboardTicketsIndex()
  {
    $response = $this->call('GET', 'account/dashboard/tickets');

    $this->assertRedirectedToRoute('register');
  }

  /**
   * Get create ticket view (Logged Out)
   * @return void
   */
  public function testGetAccountDashboardTicketsCreate()
  {
    $response = $this->call('GET', 'account/dashboard/tickets/create');

    $this->assertRedirectedToRoute('register');
  }

  /**
   * Get edit ticket view (Logged Out)
   * @return void
   */
  public function testGetAccountDashboardTicketsEdit()
  {
    $ticketId = 2;

    $response = $this->call('GET', 'account/dashboard/tickets/' . $ticketId . '/edit');

    $this->assertRedirectedToRoute('register');
  }

/////////////////////
/// ROUTES TO TEST //
/////////////////////

// |        | POST                           | account/dashboard/tickets                                     | account.dashboard.tickets.store  | App\Http\Controllers\TicketsController@store                             | auth       |
// |        | PATCH                          | account/dashboard/tickets/{tickets}                           |                                  | App\Http\Controllers\TicketsController@update                            | auth       |
// |        | PUT                            | account/dashboard/tickets/{tickets}                           | account.dashboard.tickets.update | App\Http\Controllers\TicketsController@update                            | auth       |
// |        | POST                           | account/tickets/create                                        | tickets.store                    | App\Http\Controllers\TicketsController@doCreate                          | auth       |
// |        | GET|HEAD                       | account/tickets/create/{id}                                   | tickets.create                   | App\Http\Controllers\TicketsController@showCreate                        | auth       |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | account/tickets/edit/{id}                                     | tickets.edit                     | App\Http\Controllers\TicketsController@showEdit                          | auth       |
// |        | POST                           | account/tickets/edit/{id}                                     | tickets.update                   | App\Http\Controllers\TicketsController@doEdit                            | auth       |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | account/tickets/index/{id}                                    | tickets.index                    | App\Http\Controllers\TicketsController@showIndex                         | auth       |
}
