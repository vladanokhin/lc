<?php

use src\App\Http\Controller\AppController;
use src\App\Http\Controller\LeadCollectorApiController;

/**
 * Configure db
 */
$route->get('/install', [AppController::class, 'installer']);

/**
 * Transferring last 20 minutes leads each 2 minutes
 */
$route->get('/curl-sender', [LeadCollectorApiController::class, 'curlSender']);

/**
 * Handling lost leads and leads with {Without}
 */
$route->get('/lost', [LeadCollectorApiController::class, 'lostLeads']);

/**
 * Handling missed leads older than 2 hours.
 */
$route->get('/missed', [LeadCollectorApiController::class, 'missedLeadsHandler']);

/**
 * Add second phone number
 */
$route->post('/thx', [LeadCollectorApiController::class, 'additionalData']);

/**
 * Empty page
 */
$route->get('/', [LeadCollectorApiController::class, 'index']);

/**
 * Go here if you need to get all leads. Returns JSON!
 */
$route->get('/leads', [LeadCollectorApiController::class, 'getLeads']);

/**
 * Go here if you need to get own lead. Returns JSON!
 */
$route->get('/leads/[a:id]', [LeadCollectorApiController::class, 'getLead']);

/**
 * Handling postback
 */
$route->get('/postback', [LeadCollectorApiController::class, 'postback']);

$route->get('/postback/scheme', [LeadCollectorApiController::class, 'statusSchemePostback']);

/**
 * Provide this link to handle new lead.
 * Usage example:
 * 1. Go to any landing page
 * 2. Find needed form on page.
 * 3. Type <form action='{THIS_APP_DOMAIN}/leads' method='POST'> instead your current.
 */
$route->post('/leads', [LeadCollectorApiController::class, 'registerLead']);

/**
 * Deleting lead from LC
 */
$route->delete('/delete/[a:unique]', [LeadCollectorApiController::class, 'delete']);

/**
 * Use this link if you want to refresh lead data.
 */
$route->get('/refresh/[a:click]', [LeadCollectorApiController::class, 'refreshLead']);

/**
 * Sending lead to partner again
 */
$route->get('/reorder/[a:unique]', [LeadCollectorApiController::class, 'reorder']);

$route->get('/reorder-edited', [LeadCollectorApiController::class, 'reorderEdited']);

$route->post('/leads/longform', [LeadCollectorApiController::class, 'longFormLeads']);

$route->post('/leads/datafix', [LeadCollectorApiController::class, 'leadsDataFix']);