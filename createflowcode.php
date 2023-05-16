if (! function_exists('createFlowcode')) {
    function createFlowcode($creator_id)
    {
        $user = User::find($creator_id);
        $client_id = getenv('SLICK_CLIENT_ID');
        
        $url = "https://www.paradedeck.com/creator/".$user->master_link;
        
        // generate a random string
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $flowcode_compain = '';
        $length = 10;
        for ($i = 0; $i < $length; $i++) {
            $flowcode_compain .= $characters[rand(0, $charactersLength - 1)];
        }
        $imageurl = 'https://www.paradedeck.com/avatar/'.$user->avatar;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://gateway.flowcode.com/v4/codes');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"destination\": {\n    \"destination_type\": \"SMS\",\n   
                                                \"redirect_value\": 
                                                    {\n      
                                                    \"recipient\": \"$user->phone\",\n      
                                                    \"message\": \"$user->organization_company_name\"\n    }\n  },\n  
                                                    \"code_name\": \"$flowcode_compain\",\n  
                                                    \"rules\": {\n    
                                                                    \"rules\": [\n      
                                                                                {\n        
                                                                                    \"destination\": 
                                                                                        {\n          
                                                                                            \"destination_type\": \"SMS\",\n          
                                                                                            \"redirect_value\": 
                                                                                                {\n            
                                                                                                    \"recipient\": \"$user->phone\",\n           
                                                                                                    \"message\": \"$user->organization_company_name\"\n          
                                                                                                    
                                                                                                }\n        
                                                                                            
                                                                                        },\n        
                                                                                        \"days\": [\n          0,\n          1,\n          2\n        ],\n        
                                                                                        \"start_time\": \"14:45\",\n        
                                                                                        \"end_time\": \"14:45\",\n        
                                                                                        \"device_type\": \"ANDROID\",\n        
                                                                                        \"region\": \"South\",\n        
                                                                                        \"state\": \"NY\",\n        
                                                                                        \"county\": \"New York County\",\n        
                                                                                        \"zip\": \"10013\",\n        
                                                                                        \"distance\": 10,\n        
                                                                                        \"distance_unit\": \"mi\",\n        
                                                                                        \"from_lat\": 40.75,\n        
                                                                                        \"from_lng\": -73.98\n      
                                                                                    
                                                                                }\n    
                                                                            ],\n    
                                                                            \"time_zone\": \"SCANNER\"\n  },\n  
                                                                            \"interstitials\": [\n    
                                                                            {\n      \"type\": \"geo\"\n    }\n  ],\n  
                                                                            \"folder_id\": 59015,\n  
                                                                            \"redirect_subdomain\": \"$url\",\n  
                                                                            \"tags\": [\n    \"Flowcode\",\n    \"API\"\n  ],\n  
                                                                            \"download\": false,\n  
                                                                            \"image_type\": \"svg\",\n  
                                                                            \"customization_options\": 
                                                                                {\n    \"color\": \"#000000\",\n    
                                                                                \"background_color\": \"#FFFFFF\",\n    
                                                                                \"logo_image_url\": \"$imageurl\",\n    
                                                                                \"logo_image_height\": 25,\n    
                                                                                \"logo_image_width\": 25,\n   
                                                                                \"data_pattern_shape\": \"circle\",\n    
                                                                                \"cta_text_top\": \"SCAN ME\",\n    
                                                                                \"cta_text_bottom\": \"SCAN ME\"\n  }\n}");

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Apikey: '.$client_id;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result_flowcode = json_decode($result);
        $flowcode_id = $result_flowcode->code_id;
        
        $user->flowcode_id = $flowcode_id;
        $user->flowcode_compain = $flowcode_compain;
        $user->save();
    }
}
