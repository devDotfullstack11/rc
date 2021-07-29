<section class="content-header">
            <h1> <?php echo isset($title) ? $title : 'Listing' ;?>  <small><?php echo isset($sub_title) ? $sub_title : '' ;?> </small> </h1>
            <ol class="breadcrumb">
            <?php
                if(isset($b_links)){ 
                    foreach($b_links as $link){ ?>
                    <li><a href="<?php echo $link['href'] ;?>"><i class="fa <?php echo $link['fa_icon'] ;?>"></i> <?php echo $link['link_text'] ;?></a></li>
                    
                <?php    } 

                 }
                
            ?>
            </ol>
            <?php if($this->session->flashdata('msg')){ ?>
            <div class="alert alert-success"><p><?php echo $this->session->flashdata('msg');?></p></div>
            <?php }?>
        </section>
