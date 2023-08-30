/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React,{Component} from 'react';
import {
  SafeAreaView,
  StyleSheet,
  ScrollView,
  View,
  TextInput,
  StatusBar,
  TouchableOpacity,
  Image,
  AsyncStorage
 
} from 'react-native';

import {Icon, Container, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form } from 'native-base';
import Toast, {DURATION} from 'react-native-easy-toast'
import PickerModal from 'react-native-picker-modal-view';
import { NavigationEvents,NavigationActions } from 'react-navigation';
import { WebView } from 'react-native-webview';

 
  export default class Admingraph extends Component {

    constructor(props) {
      super(props);
      this.state = {
        
      };
    }
  
  
  

    componentWillMount(){
  

    }

 

   
      logout(){
        AsyncStorage.getItem('userdata', (err, result) => {
          const item = JSON.parse(result);
          const key = item.login_session_key;
  
          let formdata = new FormData();
         formdata.append("login_session_key",key)
         formdata.append("user_id",item.user_id)
         
      
         fetch('http://idcaresteward.com/api/v1/user/logout',{
          method: 'post',
           headers: {
          'Content-Type': 'multipart/form-data',
           },
            body: formdata
           })
        .then((response) => response.json())
        .then((responseJson) => {
       
        console.log(responseJson);
         AsyncStorage.removeItem('userdata');
       // await AsyncStorage.removeItem('Season');
        //this.props.navigation.navigate('Roleselection'); 
        this.props.navigation.reset([NavigationActions.navigate({ routeName: 'Roleselection' })]);
       
        })
       .catch(err => {
     //  console.log(err)
       })  
      })
      }
     render() {
      
     return (
      <SafeAreaView style={{flex:1}} >
    <Container >
       <StatusBar barStyle='dark-content' backgroundColor='transparent' ></StatusBar>  

    
 


       <View style={{ paddingTop:15, flexDirection:'row',justifyContent:'space-around',backgroundColor:"#B22B57",height:50,borderBottomLeftRadius:20,borderBottomRightRadius:20}} >
    <View   style={{height:30, width:'30%',alignItems:'flex-start'}}>
    
    {/* <Image style={{width:15,height:15,tintColor:'white',marginLeft:10}}  source={require('./image/back.png')} ></Image> */}
        </View>
       <View style={{paddingTop:4, width:'30%',alignItems:'center'}}>
       <Text style={{fontWeight:'bold',color:'white',fontSize:15}}>Reports</Text>
       </View>
       <TouchableOpacity  onPress={this.logout.bind(this)} style={{marginBottom:5, width:'31%',alignItems:'flex-end'}}>
      
       <Image style={{marginRight:15, width:15,height:20,tintColor:'white'}}  source={require('./image/logout.png')} ></Image>
     </TouchableOpacity>
    
       </View> 

   
  
   <WebView
        source={{ uri: 'http://idcaresteward.com/reports/app' }}
        //style={{ marginTop: 20 }}
      />

  </Container>
  </SafeAreaView>

  );
}
};


const styles = StyleSheet.create({
  textInput: {
    minHeight: 28,
    fontSize: 12,
  //  fontFamily: AppFonts.HNMedium,
    backgroundColor: 'transparent',
    color: '#fff',
    width: "90%",
    letterSpacing: 0.9,
    borderRadius:25,
    borderColor:'transparent',
    },
  
});

