<?php 

 $details        = array();  $i = 0;  
 $existing_auths = array();
 $ordered_now    = FALSE;
 
    $new_list = array();
 
 
    $uniquer = array();
  
        foreach ($orders as $key => $order): 
        
            if(empty($order->order_time) && $order->virtual == 1)
                continue;
            
            if(!is_null($order->oid)){
                if(in_array($order->oid, $uniquer))
                    continue;

                $uniquer[] = $order->oid;
            }
            
            if($order->payed_by == 0){
                $new_list[$order->order_id][] = $order;
            }
            else{
                $new_list[$order->payed_by][] = $order;
            }
            
        endforeach;
            //echo "<pre>";var_dump($new_list); echo "</pre>"; die;

        foreach ($new_list as $key => $orders)
        {
            foreach ($orders as $ids => $order)
            {
                
            
                $attr = array();
        
                $attribute_data = json_decode(trim($order->attribute), true);        

                if(is_array($attribute_data)){
                    foreach ($attribute_data as $attrs){
                        $attr[] = $attrs;
                    }
                }

                $attr_txt = implode(', ', $attr);

                $attr_actual_text = !empty($attr_txt) ? $attr_txt : '';     
                
                //$order->id = $key;
                $order->id = $key;

                $details['master'.$order->id]['tname']          = $order->tname;
                $details['master'.$order->id]['address']        = $order->address;
                $details['master'.$order->id]['virtual']        = $order->virtual;
                
                if(!empty($order->mode))
                    $details['master'.$order->id]['mode']           = $order->mode;

                $details['master'.$order->id]['meal'][]         = '(Category) '.$order->category.', (Product) '.$order->mname.', (Attributes) '.$attr_actual_text;   
                $details['master'.$order->id]['time'][]         = $order->order_time;
                $details['master'.$order->id]['rtime']          = $order->reserved_time;
                $details['master'.$order->id]['qty'][]          = $order->qty;
                $details['master'.$order->id]['ptime'][]        = $order->process_time;
                $details['master'.$order->id]['wprocess'][]     = $order->waiter_process_time;
                $details['master'.$order->id]['kitchen'][]      = $order->kitchen_left;
                $details['master'.$order->id]['processed'][]    = $order->processed;
                $details['master'.$order->id]['oid'][]          = $order->oid;
                $details['master'.$order->id]['comment'][]      = $order->comment;
                $details['master'.$order->id]['customer_name'][]= $order->customer_name;
                $details['master'.$order->id]['under_18'][]     = $order->under_18;

                $details['master'.$order->id]['status']         = (isset($details['master'.$order->id]['status']) && $details['master'.$order->id]['status'] == 'paybill') ? 'paybill' : $order->status ; 

                $details['master'.$order->id]['order_id']       = $order->order_id;

                if ($order->payed_by == 0) 
                {    
                    $details['master'.$order->id]['master_id']  = $order->order_id;
                    $details['master'.$order->id]['price']      = $order->price;
                    $details['master'.$order->id]['delivery']   = $order->delivery_charge;
                    $details['master'.$order->id]['tip']        = $order->tip;
                    
                }

                ++$i;        unset($attr);
            }
        }
        
    //echo "<pre>";var_dump($details); echo "</pre>"; die;
    
    //echo "<pre>";var_dump($new_list); echo "</pre>"; die;
    
    /*
    foreach ($orders as $key => $order): 
        
        if(!is_null($order->oid)){
            if(in_array($order->oid, $uniquer))
                continue;
            
            $uniquer[] = $order->oid;
        }
        
        
                
        $attr = array();
        
        $attribute_data = json_decode(trim($order->attribute), true);        
        
        if(is_array($attribute_data)){
            foreach ($attribute_data as $attrs){
                $attr[] = $attrs;
            }
        }
        
        $attr_txt = implode(', ', $attr);
        
        $attr_actual_text = !empty($attr_txt) ? ' ('.  implode(', ', $attr).') ' : ''; 
        
        echo $order->payed_by;
        if (in_array($order->payed_by, $existing_auths) || $order->payed_by == 0)  
        {    
            $details['master'.$order->id]['tname']          = $order->tname;
            if(!empty($order->mode))
            $details['master'.$order->id]['mode']           = $order->mode;
            
            $details['master'.$order->id]['meal'][]         = $order->mname.$attr_actual_text;   
            $details['master'.$order->id]['time'][]         = $order->order_time;
            $details['master'.$order->id]['rtime']          = $order->reserved_time;
            $details['master'.$order->id]['qty'][]          = $order->qty;
            $details['master'.$order->id]['ptime'][]        = $order->process_time;
            $details['master'.$order->id]['wprocess'][]     = $order->waiter_process_time;
            $details['master'.$order->id]['kitchen'][]      = $order->kitchen_left;
            $details['master'.$order->id]['processed'][]    = $order->processed;
            $details['master'.$order->id]['oid'][]          = $order->oid;
            $details['master'.$order->id]['comment'][]      = $order->comment;
            $details['master'.$order->id]['customer_name'][]= $order->customer_name;
            
            
            $details['master'.$order->id]['status']         = (isset($details['master'.$order->id]['status']) && $details['master'.$order->id]['status'] == 'paybill') ? 'paybill' : $order->status ; 
            
            $details['master'.$order->id]['order_id']       = $order->order_id;
            
            if ($order->master == 1) 
            {    
                $details['master'.$order->id]['master_id']  = $order->order_id;
                $details['master'.$order->id]['price']      = $order->price;
                $details['master'.$order->id]['tip']        = $order->tip;
            }
                
        }
        else
        {
            $details[$order->order_id]['customer_name'][]= $order->customer_name;
            
            if(!empty($order->mode))
            $details[$order->order_id]['mode']           = $order->mode;
            
            $details[$order->order_id]['comment'][]      = $order->comment;
            $details[$order->order_id]['tname']          = $order->tname; 
            $details[$order->order_id]['meal'][]         = $order->mname.$attr_actual_text; 
            $details[$order->order_id]['time'][]         = $order->order_time;
            $details[$order->order_id]['rtime']          = $order->reserved_time;
            $details[$order->order_id]['ptime'][]        = $order->process_time;
            $details[$order->order_id]['wprocess'][]     = $order->waiter_process_time;
            $details[$order->order_id]['kitchen'][]      = $order->kitchen_left;
            $details[$order->order_id]['qty'][]          = $order->qty;
            $details[$order->order_id]['processed'][]    = $order->processed;
            $details[$order->order_id]['oid'][]          = $order->oid;
            $details[$order->order_id]['status']         = (isset($details[$order->order_id]['status']) && $details[$order->order_id]['status'] == 'paybill') ? 'paybill' : $order->status ; 
            $details[$order->order_id]['price']          = $order->price ;
            $details[$order->order_id]['tip']            = $order->tip ;
            $details[$order->order_id]['order_id']       = $order->order_id; 
            $details[$order->order_id]['master_id']      = $order->order_id; 
        }
        
        $existing_auths[] = $order->payed_by;
        $existing_auths[] = $order->id;
        $existing_auths[] = $order->order_id;
        
        ++$i;        unset($attr);
        
    endforeach;
    //echo '<pre>'; var_dump($details); echo '</pre>';
    

/*
    $details = array();  $i = 0;  
    
    $uniquer = array();
  
    foreach ($orders as $key => $order): 
        
        if(!is_null($order->oid)){
            if(in_array($order->oid, $uniquer))
                continue;
            
            $uniquer[] = $order->oid;
        }
        
        $attr = array();
        
        $attribute_data = json_decode(trim($order->attribute), true);        
        
        if(is_array($attribute_data)){
            foreach ($attribute_data as $attrs){
                $attr[] = $attrs;
            }
        }
        
        $attr_txt = implode(', ', $attr);
        
        $attr_actual_text = !empty($attr_txt) ? ' ('.  implode(', ', $attr).') ' : ''; 
        
        if ($order->master == 1 || $order->self == 0)  
        {    
            $details['master'.$order->id]['tname']          = $order->tname;
            if(!empty($order->mode))
            $details['master'.$order->id]['mode']           = $order->mode;
            
            $details['master'.$order->id]['meal'][]         = $order->mname.$attr_actual_text;   
            $details['master'.$order->id]['time'][]         = $order->order_time;
            $details['master'.$order->id]['rtime']          = $order->reserved_time;
            $details['master'.$order->id]['qty'][]          = $order->qty;
            $details['master'.$order->id]['ptime'][]        = $order->process_time;
            $details['master'.$order->id]['wprocess'][]     = $order->waiter_process_time;
            $details['master'.$order->id]['kitchen'][]      = $order->kitchen_left;
            $details['master'.$order->id]['processed'][]    = $order->processed;
            $details['master'.$order->id]['oid'][]          = $order->oid;
            $details['master'.$order->id]['comment'][]      = $order->comment;
            $details['master'.$order->id]['customer_name'][]= $order->customer_name;
            
            
            $details['master'.$order->id]['status']         = (isset($details['master'.$order->id]['status']) && $details['master'.$order->id]['status'] == 'paybill') ? 'paybill' : $order->status ; 
            
            $details['master'.$order->id]['order_id']       = $order->order_id;
            
            if ($order->master == 1) 
            {    
                $details['master'.$order->id]['master_id']  = $order->order_id;
                $details['master'.$order->id]['price']      = $order->price;
                $details['master'.$order->id]['tip']        = $order->tip;
            }
                
        }
        else
        {
            $details[$order->order_id]['customer_name'][]= $order->customer_name;
            
            if(!empty($order->mode))
            $details[$order->order_id]['mode']           = $order->mode;
            
            $details[$order->order_id]['comment'][]      = $order->comment;
            $details[$order->order_id]['tname']          = $order->tname; 
            $details[$order->order_id]['meal'][]         = $order->mname.$attr_actual_text; 
            $details[$order->order_id]['time'][]         = $order->order_time;
            $details[$order->order_id]['rtime']          = $order->reserved_time;
            $details[$order->order_id]['ptime'][]        = $order->process_time;
            $details[$order->order_id]['wprocess'][]     = $order->waiter_process_time;
            $details[$order->order_id]['kitchen'][]      = $order->kitchen_left;
            $details[$order->order_id]['qty'][]          = $order->qty;
            $details[$order->order_id]['processed'][]    = $order->processed;
            $details[$order->order_id]['oid'][]          = $order->oid;
            $details[$order->order_id]['status']         = (isset($details[$order->order_id]['status']) && $details[$order->order_id]['status'] == 'paybill') ? 'paybill' : $order->status ; 
            $details[$order->order_id]['price']          = $order->price ;
            $details[$order->order_id]['tip']            = $order->tip ;
            $details[$order->order_id]['order_id']       = $order->order_id; 
            $details[$order->order_id]['master_id']      = $order->order_id; 
        }
        
        ++$i;        unset($attr);
        
    endforeach;
    //echo '<pre>'; var_dump($details); echo '</pre>';
    */
    ?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">Start Bootstrap</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Content -->
<div class="container">

  <!-- Page Heading -->
  <h1 class="my-4">Page Heading
    <small>Secondary Text</small>
  </h1>

  <div class="row">
    <div class="col-lg-6 portfolio-item">
      <div class="card h-100">
        <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
        <div class="card-body">
          <h4 class="card-title">
            <a href="#">Project One</a>
          </h4>
          <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>
        </div>
      </div>
    </div>
    <div class="col-lg-6 portfolio-item">
      <div class="card h-100">
        <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
        <div class="card-body">
          <h4 class="card-title">
            <a href="#">Project Two</a>
          </h4>
          <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit aliquam aperiam nulla perferendis dolor nobis numquam, rem expedita, aliquid optio, alias illum eaque. Non magni, voluptates quae, necessitatibus unde temporibus.</p>
        </div>
      </div>
    </div>
    <div class="col-lg-6 portfolio-item">
      <div class="card h-100">
        <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
        <div class="card-body">
          <h4 class="card-title">
            <a href="#">Project Three</a>
          </h4>
          <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>
        </div>
      </div>
    </div>
    <div class="col-lg-6 portfolio-item">
      <div class="card h-100">
        <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
        <div class="card-body">
          <h4 class="card-title">
            <a href="#">Project Four</a>
          </h4>
          <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit aliquam aperiam nulla perferendis dolor nobis numquam, rem expedita, aliquid optio, alias illum eaque. Non magni, voluptates quae, necessitatibus unde temporibus.</p>
        </div>
      </div>
    </div>
    <div class="col-lg-6 portfolio-item">
      <div class="card h-100">
        <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
        <div class="card-body">
          <h4 class="card-title">
            <a href="#">Project Five</a>
          </h4>
          <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>
        </div>
      </div>
    </div>
    <div class="col-lg-6 portfolio-item">
      <div class="card h-100">
        <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
        <div class="card-body">
          <h4 class="card-title">
            <a href="#">Project Six</a>
          </h4>
          <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit aliquam aperiam nulla perferendis dolor nobis numquam, rem expedita, aliquid optio, alias illum eaque. Non magni, voluptates quae, necessitatibus unde temporibus.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->

  <!-- Pagination -->
  <ul class="pagination justify-content-center">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#">1</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#">2</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#">3</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>

</div>
<!-- /.container -->

<!-- Footer -->
<footer class="py-5 bg-dark">
  <div class="container">
    <p class="m-0 text-center text-white">Copyright &copy; Your Website 2018</p>
  </div>
  <!-- /.container -->
</footer>

    <div class="flash_red flash_blue hidden"></div>
   