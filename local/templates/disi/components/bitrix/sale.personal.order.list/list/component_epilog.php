<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<script>
    let params = (new URL(document.location)).searchParams;
    let selectedYear = params.get("year");

    console.log(selectedYear)

    if(selectedYear !== 'all' && selectedYear !== null){
        let orderItemColl = document.querySelectorAll('.js-order-item')
        if (orderItemColl) {
            orderItemColl.forEach(function (orderItem){
                let orderDate = orderItem.getAttribute('data-order-year')
                if(orderDate !== selectedYear){
                    orderItem.classList.add('d-none')
                }

            })
        }
    }
</script>