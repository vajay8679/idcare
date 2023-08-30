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
import { NavigationActions, StackActions } from 'react-navigation';
import { Container,Icon, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form } from 'native-base';


const CustomHeader = ({ navigation }) => (

    <View style={{ flexDirection: 'row' ,justifyContent:'center',backgroundColor:'blue'}}>
     <TouchableOpacity onPress={()=> navigation.goBack(null)} style={{flex:1,justifyContent:"flex-start"}} transparent>
     <Image
      source={require('./image/BackLight.png')}
    /> 
   
            </TouchableOpacity>
            
      <View style={{  justifyContent: 'center', paddingRight: 15 }}>
        {/* <Text>Conference</Text> */}
      </View>

    </View>

);
 
  export default class Roleselection extends Component {
    // static navigationOptions = ({ navigation }) => {
    //   return {
    //     header: <CustomHeader this navigation={navigation} />
    //   };
    // };
    constructor(props) {
      super(props);
      this.state = {
        animate1:false,
        dataSource:[],
        FirstName: "",
        LastName: "",
        BirthDate: "",
        Email: "",
        UserName: "",
        Password: "",
        PasswordConfirmation:"",
        Gender: "",
        PhoneNumber: "",
        MobileCode : "",
        CountryId: "",
        ZipCode:"",
        Address: "",
        DeviceId:"123",
        ReferralCode: "",
        Source: "Direct",
        UserTypeID: '2',
        SourceGUID: "",
        DeviceType: "Native",
        DeviceGUID: "",
        DeviceToken: "",
        IPAddress: "",
        Latitude:"",
        Longitude:"",
        error: {},
        animate:false,
         selectedDate: "",
        isDateTimePickerVisible: false,
        animate2:false,
        isLoading: false,
        isSignInButtonEnable:false,
      };
    }

    componentWillMount(){
      AsyncStorage.getItem('userdata', (err, result) => {
console.log(result)
        const item = JSON.parse(result);
     //   const key = item.login_session_key;

        //const item = JSON.parse(result);
        var Result = result;
       // console.log(Result)
        if (Result !== null && Result !== '' ) {
        if(item.login_role=='Admin')
        {
           this.props.navigation.reset([NavigationActions.navigate({ routeName: 'Admingraph' })], 0);
        }
        else{
          this.props.navigation.reset([NavigationActions.navigate({ routeName: 'Home' })], 0);
        }

        } else {
          return null
          }
      })
    }
    // static navigationOptions = {
    //   title: "Welcome"
    // }
    godata(){
     
      this.props.navigation.navigate('Login',{login_type:'MDSTEWARD',rolename:'MD Steward'});
    }
    godataadmin(){
      this.props.navigation.navigate('Login',{login_type:'ADMIN',rolename:'Admin'});
    }
    godataoperator(){
      this.props.navigation.navigate('Login',{login_type:'DATAOPERATOR',rolename:'Data Operator' });
    }
   
    render() {
  return (
  
    <Container>
    {/* <StatusBar translucent={true}></StatusBar>  */}
    {/* <Header />  */}

     
{/*  
        <Content> */}
        <View style={{ flex:1,  flexDirection:'column', alignItems:'center',justifyContent:'space-evenly'}}>
          <TouchableOpacity onPress={this.godata.bind(this)} style={{height:'37%',alignItems:'center',justifyContent:'center',backgroundColor:'#474A7F',width:'100%'}}>
  
           {/* <Icon name='person' style={{fontSize: 35, color: 'white'}} /> */}
           <Image style={{width:30,height:30,borderRadius:5,tintColor:'white'}}  source={require('./image/doctor.png')} ></Image> 
         
           <Text style={{fontSize:18,color:'white'}}>MD Steward</Text> 
           
           
           </TouchableOpacity>
           <View style={{backgroundColor:'#474A7F',width:'100%',height:'35%'}}>
           <TouchableOpacity onPress={this.godataoperator.bind(this)} style={{ borderTopLeftRadius:30,borderTopRightRadius:30, alignItems:'center',justifyContent:'center',width:'100%', backgroundColor:'#B22B57',alignItems:'center',height:'100%'}}>
           {/* <Icon name='person' style={{fontSize: 35, color: 'white'}} /> */}
              
           <Image style={{width:30,height:30,borderRadius:5,tintColor:'white'}}  source={require('./image/clipboard.png')} ></Image> 
           <Text style={{color:'white',fontSize:18}}>Data Operator</Text> 
           </TouchableOpacity>
           </View>
           <View style={{backgroundColor:'#B22B57',width:'100%',height:'35%'}}>
           <TouchableOpacity onPress={this.godataadmin.bind(this)} style={{ borderTopLeftRadius:30,borderTopRightRadius:30, alignItems:'center',justifyContent:'center',width:'100%', backgroundColor:'white',alignItems:'center',height:'100%'}}>
           {/* <Icon name='person' style={{fontSize: 35, color: '#B22B57'}} /> */}
           <Image style={{width:30,height:30,borderRadius:5}}  source={require('./image/adminpic.png')} ></Image> 
           <Text style={{color:'#B22B57',fontSize:18}}>Admin</Text> 
           </TouchableOpacity>
           </View>
        </View>
       {/* </Content> */}

    {/* <Footer style={{ backgroundColor:'white',borderColor:'red', height:'20%',width:'98%', alignSelf:'center', borderTopWidth: -50,
 backgroundColor:'red',borderTopRightRadius:20,borderTopLeftRadius:20}}>
      <FooterTab style={{ borderTopWidth: -50, borderTopWidth:5,borderColor:'red', borderTopLeftRadius:20,backgroundColor:'red',width:'94%',borderTopRightRadius:20}} >
        <Button >
          <Text>Footer</Text>
        </Button>
      </FooterTab>
    </Footer> */}
  </Container>


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

