<?php
    return [        

        App\Core\Route::get('|^home/?$|',                   'Main',             'home'),

        App\Core\Route::get('|^matches/?$|',                'MatchInfo',        'matches'),
        App\Core\Route::get('|^matches/orderbytemp/?$|',    'MatchInfo',        'orderbytemp'),
        App\Core\Route::get('|^teams/?$|',                  'Team',             'teams'),
        App\Core\Route::get('|^teams/results/?$|',          'Team',             'teamResults'),
        App\Core\Route::get('|^groups/?$|',                 'GroupInfo',        'groups'),
        App\Core\Route::get('|^players/?$|',                'Player',           'players'),

        # API routes:
        App\Core\Route::get('|^api/init/?$|',               'ApiInit',           'init'),

        App\Core\Route::any('|^.*$|',                       'Main',             'landing')
    ];