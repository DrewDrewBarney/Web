
@props(['pageName'=>'XXX'])

<nav >
    <ul class=breadcrumb>

        <?php 
            function style($truth){
                return $truth ? "class=selectedCrumb" : "";
            };
        ?>
        
        <li {{style($pageName=="home")}}><a href="/" >HOME</a></li>
        <li {{style($pageName=="aboutMe")}}><a href="/aboutme" >ABOUT</a></li>
        <li {{style($pageName=="contact")}}><a href="/contact" >CONTACT</a></li>
        <li {{style($pageName=="physiology")}}><a href="/physiology" >PHYSIOLOGY</a></li>
        <li {{style($pageName=="intervalPaces")}}><a href="/intervalPaces" >INTERVAL PACES</a></li>

        
            
    </ul>
</nav>