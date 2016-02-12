<?php


class CategoriesClickTest extends TestCase {

 /**
   * Will take user to search results with active events as query (Logged out)
   *
   *
   */
  public function testCategoryActive()
  {
    $this->visit('events/categories')
         ->click('active')
         ->seePageIs('events/search?q=active');

  }

 /**
   * Will take user to search results with arts as query (Logged out)
   *
   *
   */
  public function testCategoryArts()
  {
    $this->visit('events/categories')
         ->click('arts')
         ->seePageIs('events/search?q=arts');

  }

 /**
   * Will take user to search results with automotive as query (Logged out)
   *
   *
   */
  public function testCategoryAutomotive()
  {
    $this->visit('events/categories')
         ->click('automotive')
         ->seePageIs('events/search?q=automotive');

  }

 /**
   * Will take user to search results with bers as query (Logged out)
   *
   *
   */
  public function testCategoryBars()
  {
    $this->visit('events/categories')
         ->click('bars')
         ->seePageIs('events/search?q=bars');

  }

 /**
   * Will take user to search results with beauty as query (Logged out)
   *
   *
   */
  public function testCategoryBeauty()
  {
    $this->visit('events/categories')
         ->click('beauty')
         ->seePageIs('events/search?q=beauty');

  }

 /**
   * Will take user to search results with casinos as query (Logged out)
   *
   *
   */
  public function testCategoryCasinos()
  {
    $this->visit('events/categories')
         ->click('casinos')
         ->seePageIs('events/search?q=casinos');

  }

 /**
   * Will take user to search results with concerts as query (Logged out)
   *
   *
   */
  public function testCategoryConcerts()
  {
    $this->visit('events/categories')
         ->click('concerts')
         ->seePageIs('events/search?q=concerts');

  }

 /**
   * Will take user to search results with conventions as query (Logged out)
   *
   *
   */
  public function testCategoryConventions()
  {
    $this->visit('events/categories')
         ->click('conventions')
         ->seePageIs('events/search?q=conventions');

  }

 /**
   * Will take user to search results with educational as query (Logged out)
   *
   *
   */
  public function testCategoryEducational()
  {
    $this->visit('events/categories')
         ->click('educational')
         ->seePageIs('events/search?q=educational');

  }

 /**
   * Will take user to search results with entertainment as query (Logged out)
   *
   *
   */
  public function testCategoryEntertainment()
  {
    $this->visit('events/categories')
         ->click('entertainment')
         ->seePageIs('events/search?q=entertainment');

  }

 /**
   * Will take user to search results with events as query (Logged out)
   *
   *
   */
  public function testCategoryEvents()
  {
    $this->visit('events/categories')
         ->click('events')
         ->seePageIs('events/search?q=events');

  }

 /**
   * Will take user to search results with festivals as query (Logged out)
   *
   *
   */
  public function testCategoryFestivals()
  {
    $this->visit('events/categories')
         ->click('festivals')
         ->seePageIs('events/search?q=festivals');

  }

 /**
   * Will take user to search results with financial as query (Logged out)
   *
   *
   */
  public function testCategoryFinancial()
  {
    $this->visit('events/categories')
         ->click('financial')
         ->seePageIs('events/search?q=financial');

  }

 /**
   * Will take user to search results with games as query (Logged out)
   *
   *
   */
  public function testCategoryGames()
  {
    $this->visit('events/categories')
         ->click('games')
         ->seePageIs('events/search?q=games');

  }

 /**
   * Will take user to search results with government as query (Logged out)
   *
   *
   */
  public function testCategoryGovernment()
  {
    $this->visit('events/categories')
         ->click('government')
         ->seePageIs('events/search?q=government');

  }

   /**
   * Will take user to search results with health as query (Logged out)
   *
   *
   */
  public function testCategoryHealth()
  {
    $this->visit('events/categories')
         ->click('health')
         ->seePageIs('events/search?q=health');

  }

   /**
   * Will take user to search results with home as query (Logged out)
   *
   *
   */
  public function testCategoryHome()
  {
    $this->visit('events/categories')
         ->click('home')
         ->seePageIs('events/search?q=home');

  }

   /**
   * Will take user to search results with hotels as query (Logged out)
   *
   *
   */
  public function testCategoryHotels()
  {
    $this->visit('events/categories')
         ->click('hotels')
         ->seePageIs('events/search?q=hotels');

  }

   /**
   * Will take user to search results with kids as query (Logged out)
   *
   *
   */
  public function testCategoryKids()
  {
    $this->visit('events/categories')
         ->click('kids')
         ->seePageIs('events/search?q=kids');

  }

   /**
   * Will take user to search results with local flavor as query (Logged out)
   *
   *
   */
  public function testCategoryLocalFlavor()
  {
    $this->visit('events/categories')
         ->click('local flavor')
         ->seePageIs('events/search?q=local-flavor');

  }

  /**
   * Will take user to search results with local services as query (Logged out)
   *
   *
   */
  public function testCategoryLocalServices()
  {
    $this->visit('events/categories')
         ->click('local services')
         ->seePageIs('events/search?q=local-services');

  }

  /**
   * Will take user to search results with media as query (Logged out)
   *
   *
   */
  public function testCategoryMedia()
  {
    $this->visit('events/categories')
         ->click('media')
         ->seePageIs('events/search?q=media');

  }

  /**
   * Will take user to search results with medical as query (Logged out)
   *
   *
   */
  public function testCategoryMedical()
  {
    $this->visit('events/categories')
         ->click('medical')
         ->seePageIs('events/search?q=medical');

  }

  /**
   * Will take user to search results with nightlife as query (Logged out)
   *
   *
   */
  public function testCategoryNightlife()
  {
    $this->visit('events/categories')
         ->click('nightlife')
         ->seePageIs('events/search?q=nightlife');

  }

  /**
   * Will take user to search results with parties as query (Logged out)
   *
   *
   */
  public function testCategoryParties()
  {
    $this->visit('events/categories')
         ->click('parties')
         ->seePageIs('events/search?q=parties');

  }

  /**
   * Will take user to search results with pets as query (Logged out)
   *
   *
   */
  public function testCategoryPets()
  {
    $this->visit('events/categories')
         ->click('pets')
         ->seePageIs('events/search?q=pets');

  }

  /**
   * Will take user to search results with professional services as query (Logged out)
   *
   *
   */
  public function testCategoryProfessionalServices()
  {
    $this->visit('events/categories')
         ->click('professional services')
         ->seePageIs('events/search?q=professional-services');

  }

  /**
   * Will take user to search results with religious as query (Logged out)
   *
   *
   */
  public function testCategoryReligious()
  {
    $this->visit('events/categories')
         ->click('religious')
         ->seePageIs('events/search?q=religious');

  }

  /**
   * Will take user to search results with restaurants as query (Logged out)
   *
   *
   */
  public function testCategoryRestaurants()
  {
    $this->visit('events/categories')
         ->click('restaurants')
         ->seePageIs('events/search?q=restaurants');

  }

  /**
   * Will take user to search results with shopping as query (Logged out)
   *
   *
   */
  public function testCategoryShopping()
  {
    $this->visit('events/categories')
         ->click('shopping')
         ->seePageIs('events/search?q=shopping');

  }

  /**
   * Will take user to search results with shows as query (Logged out)
   *
   *
   */
  public function testCategoryShows()
  {
    $this->visit('events/categories')
         ->click('shows')
         ->seePageIs('events/search?q=shows');

  }

  /**
   * Will take user to search results with spas as query (Logged out)
   *
   *
   */
  public function testCategorySpas()
  {
    $this->visit('events/categories')
         ->click('spas')
         ->seePageIs('events/search?q=spas');

  }

  /**
   * Will take user to search results with traditional as query (Logged out)
   *
   *
   */
  public function testCategoryTraditional()
  {
    $this->visit('events/categories')
         ->click('traditional')
         ->seePageIs('events/search?q=traditional');

  }

  /**
   * Will take user to search results with travel as query (Logged out)
   *
   *
   */
  public function testCategoryTravel()
  {
    $this->visit('events/categories')
         ->click('travel')
         ->seePageIs('events/search?q=travel');

  }

}
