<?php

namespace damidevelopment\apiutils\definitions;


/**
 * @Author: Jakub Hrášek
 * @Date:   2018-06-27 12:31:23
 *
 * @SWG\Definition(
 *     definition="PaginationMeta",
 *     description="",
 *     type="object",
 *     @SWG\Property(property="totalCount", description="", type="integer", example=6),
 *     @SWG\Property(property="pageCount", description="", type="integer", example=1),
 *     @SWG\Property(property="currentPage", description="", type="integer", example=1),
 *     @SWG\Property(property="perPage", description="", type="integer", example=20)
 * )
 *
 * @SWG\Definition(
 *     definition="PaginationLinks",
 *     description="",
 *     type="object",
 *     @SWG\Property(property="self", description="call current page", type="object", ref="#/definitions/PaginationLinkObject"),
 *     @SWG\Property(property="first", description="call first page", type="object", ref="#/definitions/PaginationLinkObject"),
 *     @SWG\Property(property="last", description="call last page", type="object", ref="#/definitions/PaginationLinkObject"),
 *     @SWG\Property(property="next", description="call next page", type="object", ref="#/definitions/PaginationLinkObject"),
 *     @SWG\Property(property="prev", description="call previous page", type="object", ref="#/definitions/PaginationLinkObject")
 * )
 *
 * @SWG\Definition(
 *     definition="PaginationLinkObject",
 *     description="",
 *     type="object",
 *     @SWG\Property(property="href", description="", type="string", example="/api/method/uri")
 * )
 */
class Pagination
{
}