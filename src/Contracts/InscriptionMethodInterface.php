<?php

namespace Trustenterprises\LaravelHashgraph\Contracts;

use Trustenterprises\LaravelHashgraph\Models\Inscriptions\BurnInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\DeployInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\MintInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\InscriptionResponse;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\TransferInscription;

interface InscriptionMethodInterface
{
    /**
     * @param DeployInscription $request
     *
     * @return InscriptionResponse
     */
    public function deployInscription(DeployInscription $request): InscriptionResponse;

    /**
     * @param MintInscription $request
     * @return InscriptionResponse
     */
    public function mintInscription(MintInscription $request): InscriptionResponse;

    /**
     * @param BurnInscription $request
     * @return InscriptionResponse
     */
    public function burnInscription(BurnInscription $request): InscriptionResponse;

    /**
     * @param TransferInscription $request
     * @return InscriptionResponse
     */
    public function transferInscription(TransferInscription $request): InscriptionResponse;
}
