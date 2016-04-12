<?php

namespace Sebaks\ZendMvcController;

interface ErrorInterface
{
    /**
     * @return mixed
     */
    public function methodNotAllowed();

    /**
     * @return mixed
     */
    public function notFoundByRequestedCriteria($criteriaErrors);

    /**
     * @return mixed
     */
    public function changesErrors($changesErrors);
}
