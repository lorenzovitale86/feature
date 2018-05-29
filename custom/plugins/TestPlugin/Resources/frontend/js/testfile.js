;(function ($, window) {
    'use strict';

    $.plugin('testfile',{
        defaults: {
            playerUrl:'/shopware/test/teamPlayers/',
            preferenceUrl:'/shopware/test/ajaxSavePreference',
            loadingLabel: 'Loading team players',
            favoritePlayer: 'Choose Your Favorite Player',
            updateOk: 'Team and Player updated correctly',
            updateError: 'An error is occurred on updating of the team and player'


            },

        init: function() {
            let me = this;
            me.applyDataAttributes();
            me.registerEvents();

        },

        registerEvents: function() {
            let me =this;

         /*   me.$select = me.$el.find('*[name="profile[team]"]');
            me._on(me.$select, 'change', function (event) {

                console.log("VALORE TEAM: "+$(this).val());
            });
          */
         $('#selteam').on('change',function() {
             let team = $(this).val();
             let selplayer =  $('#selplayer');
                 $.ajax({
                     type:"POST",
                     url: me.opts.playerUrl,
                     dataType: 'json',
                     data: {
                         'idteam': team
                     }
                 }).done(function (result) {
                     selplayer.attr('disabled',true);
                     selplayer.html('<option disabled selected>'+me.opts.loadingLabel+'</option>');

                     let temp='<option value="" disabled="disabled"  selected>'+me.opts.favoritePlayer+'*</option>';
                     $.each(result.data, function(index, item) {
                     temp+='<option value="'+item.id+'">'+item.name+' '+item.surname+'</option>';

                     });
                     selplayer.html(temp);
                     selplayer.attr('disabled',false);

                 }).fail(function (jqXHR, textStatus){
                     alert( "Triggered fail callback: " + textStatus );
                 })});

         $('#preferenceForm').submit(function(e){
             e.preventDefault();

             let team = $('#selteam').val();
             let player = $('#selplayer').val();
             $.ajax({
                     type:"POST",dataType:"JSON",
                     url: me.opts.preferenceUrl,
                           data: {
                         'team': team,
                         'player':player
                     }
                 }).done(function (result) {
                    let temp='';
                     if(result.res=='success')

                      temp ='<div class="alert is--success is--rounded">' +
                         '<div class="alert--icon">' +
                         '<i class="icon--element icon--check"></i>' +
                         '</div>' +
                         '<div class="alert--content">' + me.opts.updateOk +
                         '.' +
                         '</div>' +
                         '</div>';
                     else if(result.res=='fail')
                          temp ='<div class="alert is--alert is--rounded">' +
                             '<div class="alert--icon">' +
                             '<i class="icon--element icon--remove"></i>' +
                             '</div>' +
                             '<div class="alert--content">' + me.opts.updateError +
                             '.' +
                             '</div>' +
                             '</div>';
                    $('#msg').html(temp);
                 $('#savePreverence').attr('disabled',false);
                 $('.js--loading').remove();
                 
                 }).fail(function (jqXHR, textStatus,err){
                     alert( "err: "+err+"   --- Triggered fail callback: " + textStatus );
                 })
         });


        }
    });


})(jQuery, window);