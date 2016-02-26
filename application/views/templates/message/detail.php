<script type="text/template" id="message-detail-template">
  <div class="col-lg-12" id="">
    <blockquote class='<%= position %>' style='width:100%; display:block; border-bottom: 1px #bbb solid; margin-bottom: 20px;'>
      <div class="col-lg-12">
          <span style="color:#444;"><%= name %></span>
          <a href="javascript:;" style="display: none;">
            <span>
              <i title='Delete Message' data-placement="left"
                 class="fa fa-1x fa-remove <%= position %>">
              </i>
            </span>
          </a> 
      </div>
      <div class="col-lg-2">
      <% if (typeof(img) !== "undefined") { %>
        <img src="http://placehold.it/50x50" class="thumbnail" data-toggle="popover" data-placement="right" title="<%= name %>" style="width:50px; height:50px;"><br/>
      <% } %>
      </div>
      <!-- {/if} -->
      <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <p style='word-wrap: break-word; height: 70px;' height='70px' class='dotdotdotWrapper'>
          <%= message %>
        </p>
        <span style='font-size:8px;'>
        <small>10/20/32</small>
        </span>
      </div>
    </blockquote>
  </div>
</script>