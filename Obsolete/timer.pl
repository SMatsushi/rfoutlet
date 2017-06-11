#!/usr/bin/env perl
use strict;
use warnings FATAL => 'all';
use CGI qw();
my $c = CGI->new;
print $c->header('text/plain');
if ('POST' eq $c->request_method && $c->param('dl')) {
    # yes, parameter exists
} else {
    # no
}
print 'Do not taunt happy fun CGI.';
