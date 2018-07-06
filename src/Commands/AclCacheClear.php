<?php

namespace Gurinder\LaravelAcl\Commands;


use Illuminate\Console\Command;
use Gurinder\LaravelAcl\Contracts\AclLedgerContract;

class AclCacheClear extends Command
{
    protected $signature = 'acl:clear';

    protected $description = 'Clear acl cache';

    public function handle()
    {
        try {

            $ledger = resolve(AclLedgerContract::class);

            $ledger->reset();

            if (auth()->user()) {
                $ledger->resetUserAcl(auth()->user());
            }

            $this->line('----- ACL Cache Cleared ----');

        } catch (\Exception $e) {

            $this->line($e->getMessage());

            $this->error("Something went wrong");

        }


    }
}