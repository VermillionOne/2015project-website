<?php

// ******************************************************************
// * Pages
// ******************************************************************

// robots.txt
Route::get('/robots.txt', [
  'as' => 'robots',
  'uses' => 'PagesController@robots',
]);

// Page - home
Route::get('/', [
  'as' => 'home',
  'uses' => 'PagesController@home',
]);

// Page - sell tickets landing page
Route::get('/sellTickets', [
  'as' => 'sellTickets',
  'uses' => 'PagesController@sellTickets',
]);

// Page - mobile
Route::get('/mobile', [
  'as' => 'mobile',
  'uses' => 'PagesController@mobile',
]);

// Page - tos
Route::get('/terms-conditions', [
  'as' => 'tos',
  'uses' => 'PagesController@terms',
]);

// Page - privacy
Route::get('/privacy', [
  'as' => 'privacy',
  'uses' => 'PagesController@privacy',
]);

// User public profile
Route::get('users/{username}', [
  'as' => 'profile.public',
  'uses' => 'AccountsController@showProfile',
]);

// Event public route for the private "shareable" link
Route::get('private/{hash}', [
  'as' => 'events.show.private',
  'uses' => 'EventsController@show',
]);
// ******************************************************************
// * Admin
// ******************************************************************
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {

  // Impersonate
  Route::get('impersonate/{userId}', [
    'as' => 'admin.impersonate',
    'uses' => 'AdminController@impersonate',
  ]);
});

// ******************************************************************
// * Account
// ******************************************************************
Route::group(['prefix' => 'account'], function() {

  // ******************************************************************
  // * Public
  // ******************************************************************

  // Login - get
  Route::get('login', [
    'as' => 'login',
    'uses' => 'AccountsController@showLogin',
  ]);

  // Login - post
  Route::post('login', 'AccountsController@doLogin');

  // Login - provider
  Route::get('login/{provider}', 'AccountsController@loginProvider');

  // Login - provider callback
  Route::get('login/{provider}/callback', 'AccountsController@loginProviderCallback');

  Route::get('oauth/{provider}', 'AccountsController@oath');
  Route::get('oauth/{provider}/callback', 'AccountsController@oathCallback');

  // Logout - get
  Route::get('logout', [
    'as' => 'logout',
    'uses' => 'AccountsController@doLogout',
  ]);

  // Register - get
  Route::get('register', [
    'as' => 'register',
    'uses' => 'AccountsController@showRegister'
  ]);

  // Register - post
  Route::post('register', [
    'uses' => 'AccountsController@doRegister',
  ]);

  // Email - verification
  Route::get('email/verification/{verification_code}', 'AccountsController@verifyEmail');

  // Show Password Reset
  Route::get('password/forgot', [
    'as' => 'password.forgot',
    'uses' => 'AccountsController@showForgot'
  ]);

  // Post Password Reset
  Route::post('password/forgot', [
    'as' => 'password.doForgot',
    'uses' => 'AccountsController@doForgot'
  ]);

  // Post Password Reset
  Route::get('password/reset/{token}', [
    'as' => 'password.reset',
    'uses' => 'AccountsController@showReset'
  ]);

  // Post Password Reset
  Route::post('password/reset/{token}', [
    'as' => 'password.updateReset',
    'uses' => 'AccountsController@updateReset'
  ]);

  // ******************************************************************
  // * Protected
  // ******************************************************************
  Route::group(['middleware' => 'auth'], function() {

    // Send email verification
    Route::get('email/verification/resend', 'AccountsController@resendEmailVerification');

    // // My Events
    // Route::get('my-events', [
    //   'as' => 'my-events',
    //   'uses' => 'AccountsController@showMyEvents',
    // ]);

    // User settings
    Route::get('settings', [
      'as' => 'settings',
      'uses' => 'AccountsController@showSettings',
    ]);

    // User purchased tickets
    Route::get('my-tickets', [
      'as' => 'my-tickets',
      'uses' => 'AccountsController@showMyTickets',
    ]);

    // User payments
    Route::get('payment', [
      'as' => 'payment',
      'uses' => 'AccountsController@showPayment',
    ]);

    // Update user payments
    Route::post('payment/{accountId}', [
      'as' => 'payment.update',
      'uses' => 'AccountsController@updatePayment',
    ]);

    // Account settings update
    Route::any('settings/update/account', [
      'as' => 'account.update',
      'uses' => 'AccountsController@doAccountSettings',
    ]);

    // // User settings update
    // Route::any('settings/update', [
    //   'as' => 'do.settings',
    //   'uses' => 'AccountsController@doSettings',
    // ]);

    // Account settings update password
    Route::any('settings/update/password', [
      'as' => 'password.update',
      'uses' => 'AccountsController@doAccountPassword',
    ]);

    // Security settings update
    Route::any('settings/update/security', [
      'as' => 'security.update',
      'uses' => 'AccountsController@doSecuritySettings',
    ]);

    // Billing settings update
    Route::any('settings/update/billing', [
      'as' => 'billing.update',
      'uses' => 'AccountsController@doBillingSettings',
    ]);

    // Notifications settings update
    Route::any('settings/update/notification', [
      'as' => 'notification.update',
      'uses' => 'AccountsController@doNotificationSettings',
    ]);

    // Content settings update
    Route::any('settings/update/content-restriction', [
      'as' => 'content-restriction.update',
      'uses' => 'AccountsController@doContentRestrictionSettings',
    ]);

    // // User billing
    // Route::get('credit-card', [
    //   'as' => 'credit-card',
    //   'uses' => 'AccountsController@showCreditCard',
    // ]);

    // Profile
    Route::get('profile', [
      'as' => 'profile',
      'uses' => 'AccountsController@showProfile',
    ]);

    // Users friends
    Route::get('friends', [
      'as' => 'friends',
      'uses' => 'AccountsController@showFriends'
    ]);

    // Add friends
    Route::any('friends/update/{id}', [
      'as' => 'friends.update',
      'uses' => 'AccountsController@doFriend'
    ]);

    // Delete friend request Request
    Route::any('friends/delete/request/{id}', [
      'as' => 'request.delete',
      'uses' => 'AccountsController@doDeleteRequest'
    ]);

    // Create request
    Route::get('friends/create/request/{friends}', [
      'as' => 'friends.store',
      'uses' => 'AccountsController@doCreateRequest'
    ]);

    // Index users based on search
    Route::get('friends/users', [
      'as' => 'friends.users',
      'uses' => 'AccountsController@showUsers'
    ]);

    // Delete friends
    Route::any('friends/delete/{id}', [
      'as' => 'friends.delete',
      'uses' => 'AccountsController@doDeleteFriend'
    ]);

    // Update invites from friends on click
    Route::post('friends/invites/update', [
      'as' => 'friends.invitesUpdate',
      'uses' => 'AccountsController@doUpdateInvites',
    ]);

    // Invite via friends list to created event
    Route::post('friends/create/my-event-invites', [
      'as' => 'friends.myEventInvitesCreate',
      'uses' => 'AccountsController@doMyEventInvites',
    ]);

    // wall comments
    Route::post('profile/wallcomments', 'AccountsController@doWallComments');

    // Deletes wall comments for auth profile
    Route::get('profile/wallcomments/delete/{id}', [
      'as' => 'wallComments.delete',
      'uses' => 'AccountsController@doDeleteWallComments'
    ]);

    // Profile - settings
    Route::post('profile/settings', 'AccountsController@doSettings');

    // Profile - photo
    Route::post('profile/photo', 'AccountsController@updateProfilePhoto');

    // Dashboard - tickets
    Route::resource('dashboard/tickets', 'TicketsController', ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    // User dashboard
    Route::get('dashboard', [
      'as' => 'dashboard',
      'uses' => 'TicketsController@dashboard',
    ]);

    // Show user created event details
    Route::get('dashboard/event/{id}', [
      'as' => 'events.details',
      'uses' => 'AccountsController@showDetails',
    ]);
    // Show user created event details
    Route::get('dashboard/event-delete/{id}', [
      'as' => 'events.delete',
      'uses' => 'EventsController@doEventDelete',
    ]);
    // Resend users reservation datail
    Route::post('dashboard/event/{id}/resend/{reservation}/reservation', [
      'as' => 'resend.reservation',
      'uses' => 'AccountsController@resendReservation',
    ]);

    // Check in admin
    Route::get('event/check-in', [
      'as' => 'events.check-in',
      'uses' => 'AccountsController@showCheckIn',
    ]);

    // Check in admin
    Route::get('event/check-in/confirm', [
      'as' => 'events.getTicketCode',
      'uses' => 'AccountsController@showCheckInTicketCode',
    ]);

    // Check in admin
    Route::post('event/check-in/use', [
      'as' => 'events.postTicketCode',
      'uses' => 'AccountsController@doCheckInTicketCode',
    ]);

    // Check in admin
    Route::get('event/check-in/update', [
      'as' => 'events.getTicketUpdate',
      'uses' => 'AccountsController@showCheckInUpdate',
    ]);

    // Tickets index
    Route::any('tickets/index/{id}', [
      'as' => 'tickets.index',
      'uses' => 'TicketsController@showIndex',
    ]);

    // Show edit for existing tickets
    Route::any('tickets/edit/{id}', [
      'as' => 'tickets.edit',
      'uses' => 'TicketsController@showEdit',
    ]);

    // User edit existing tickets
    Route::post('tickets/edit/{id}', [
      'as' => 'tickets.update',
      'uses' => 'TicketsController@doEdit',
    ]);

    // Update tickets from events page
    Route::get('tickets/create/{id}', [
    'as' => 'tickets.create',
    'uses' => 'TicketsController@showCreate',
    ]);

    // Post new tickets
    Route::post('tickets/create', [
    'as' => 'tickets.store',
    'uses' => 'TicketsController@doCreate',
    ]);

    // Event details photos to photo gallery
    Route::get('{slug}/gallery', [
      'as' => 'events.gallery',
      'uses' => 'EventsPhotosController@showEdit'
    ]);

    // Event details photos delete
    Route::get('gallery/{event}/delete/{id}', [
      'as' => 'events.galleryDelete',
      'uses' => 'EventsPhotosController@destroy'
    ]);

    Route::get('gallery/{event}/featured/{id}', [
      'as' => 'events.galleryFeatured',
      'uses' => 'EventsPhotosController@doFeatured'
    ]);

  });
});

// ******************************************************************
// * Events
// ******************************************************************
Route::group(['prefix' => 'events'], function() {

  // ******************************************************************
  // * Protected
  // ******************************************************************
  Route::group(['middleware' => 'auth'], function() {
    // Loading default message page for updates
    Route::get('loader', [
      'as' => 'pages.loader',
      'uses' => 'PagesController@loader',
    ]);
    // Sign upload media
    Route::get('/signuploadmedia', [
      'as' => 'events.signuploadmedia',
      'uses' => 'EventsController@signUploadMedia',
    ]);

    // Create event
    Route::get('/create/{id?}', [
      'as' => 'events.create',
      'uses' => 'EventsController@showCreate',
    ]);
    Route::post('/create/{id?}', [
      'as' => 'events.post',
      'uses' => 'EventsController@doCreate'
    ]);
    Route::put('/create/{id?}', [
      'as' => 'events upatue',
      'uses' => 'EventsController@doCreate'
    ]);
    // Get event times
    Route::get('{slug}/times', [
      'as' => 'events times',
      'uses' => 'EventsController@times'
    ]);
    // Show Schedule Index
    Route::get('{id}/schedules/index', [
      'as' => 'schedules.index',
      'uses' => 'AccountsController@showScheduleIndex',
    ]);
    // Show Update Schedule
    Route::get('{event}/schedules/{id}', [
      'as' => 'schedules.showUpdate',
      'uses' => 'AccountsController@showScheduleUpdate',
    ]);
    // Show Create Schedule
    Route::get('{id}/schedules', [
      'as' => 'schedules.showCreate',
      'uses' => 'AccountsController@showScheduleCreate',
    ]);
    // Do Schedule Delete
    Route::post('{eventId}/schedules/index/delete/schedule/{id}', [
      'as' => 'schedules.delete',
      'uses' => 'AccountsController@doScheduleDelete',
    ]);
    // Do Individual Date Delete
    Route::post('{eventId}/schedules/index/delete/date/{id}', [
      'as' => 'dates.delete',
      'uses' => 'AccountsController@doDateDelete',
    ]);
    // Do Schedule Update
    Route::any('{event}/schedules/update/{id}', [
      'as' => 'schedules.update',
      'uses' => 'AccountsController@doScheduleUpdate',
    ]);
    // Create New Event Schedule
    Route::post('schedules/create/{id}', [
      'as' => 'schedules.create',
      'uses' => 'AccountsController@doScheduleCreate',
    ]);

    // Show Update Page
    Route::any('{id}/updateevent', [
      'as' => 'events.update-event',
      'uses' => 'EventsController@showEventUpdate'
    ]);

    // Do event Update
    Route::any('{id}/updateevent/update', [
      'as' => 'events.doUpdateEvent',
      'uses' => 'EventsController@doEventUpdate'
    ]);

    // Show image update page
    Route::any('{slug}/updateimages', [
      'as' => 'events.update-images',
      'uses' => 'EventsController@showImagesUpdate'
    ]);

    // Do event update published
    Route::post('{id}/updateevent/publish', [
      'as' => 'events.doUpdatePublish',
      'uses' => 'EventsController@doEventPublish'
    ]);

  });

  // ******************************************************************
  // * Public
  // ******************************************************************

  // Event search results
  Route::get('/search', [
    'as' => 'events.search',
    'uses' => 'EventsController@doSearch',
  ]);

  Route::any('/categories', [
    'as' => 'events.categories',
    'uses' => 'EventsController@showCategories',
  ]);

  // All events
  Route::get('/allevents', [
    'as' => 'events.allevents',
    'uses' => 'EventsController@showAllEvents',
  ]);

  // Share via email
  Route::post('{slug}/invite', [
    'as' => 'events.email',
    'uses' => 'EventsController@shareViaEmail',
  ]);

  // Invite via friends list
  Route::post('{slug}/create/event-invites', [
    'as' => 'events.eventInvitesCreate',
    'uses' => 'EventsController@doEventInvites',
  ]);

  // Comments
  Route::post('{slug}/comments', [
    'as' => 'events.comments',
    'uses' => 'EventsController@doComments',
  ]);

  // Comments
  Route::post('{slug}/updateevent/updates', [
    'as' => 'events.updates',
    'uses' => 'EventsController@doEventUpdate',
  ]);
  // Enable or disable map
  Route::post('{slug}/map/update', [
    'as' => 'map.update',
    'uses' => 'EventsController@doUpdateMap',
  ]);
  // Enable or disable RSVP
  Route::post('{slug}/rsvp/update', [
    'as' => 'rsvp.update',
    'uses' => 'EventsController@doUpdateRsvp',
  ]);
  // Enable or disable comments
  Route::post('{slug}/comments/update', [
    'as' => 'comments.update',
    'uses' => 'EventsController@doUpdateComments',
  ]);
  // Enable or disable RSVP
  Route::post('{slug}/reviews/update', [
    'as' => 'reviews.update',
    'uses' => 'EventsController@doUpdateReviews',
  ]);
  // Tickets page
  Route::any('{slug}/tickets', [
    'as' => 'events.tickets',
    'uses' => 'EventsTicketsController@show',
  ]);

   // Tickets page
  Route::post('{slug}/tickets/charge', [
    'as' => 'events.tickets.charge',
    'uses' => 'EventsTicketsController@doCharge',
  ]);

   // Reservation
  Route::post('{id}/reservation/create', [
    'as' => 'events.create.reservation',
    'uses' => 'EventsTicketsController@doReserve',
  ]);

  // Tickets confirmation page
  Route::any('{slug}/confirmation', [
    'as' => 'tickets.confirmation',
    'uses' => 'EventsTicketsController@confirmation',
  ]);

  // Reservation confirmation page
  Route::get('{slug}/reservation/confirmation', [
    'as' => 'reservation.confirmation',
    'uses' => 'EventsTicketsController@showReservationConfirmation',
  ]);

  // // Event details photos
  // Route::get('{slug}/photos', [
  //   'as' => 'events.photos',
  //   'uses' => 'EventsPhotosController@show'
  // ]);

  // Event details
  Route::get('{slug}', [
    'as' => 'events.show',
    'uses' => 'EventsController@show',
  ]);

  // Event ratings
  Route::get('{slug}/reviews', [
    'as' => 'events.reviews',
    'uses' => 'EventRatingsController@show',
  ]);

  // Reviews
  Route::post('{slug}/event-ratings', [
    'as' => 'events.event-ratings',
    'uses' => 'EventRatingsController@doReviews',
  ]);

  // RSVP
  Route::post('{slug}/rsvp', [
    'as' => 'events.rsvp',
    'uses' => 'EventsController@postRsvp',
  ]);

});

// QR codes
Route::any('tickets/{hash}', [
  'uses' => 'EventsTicketsController@qr',
]);

// ******************************************************************
// * Tag
// ******************************************************************
// Route::group(['prefix' => 'tag'], function() {
//   Route::get('/{tag}', 'TagsController@show');
// });
