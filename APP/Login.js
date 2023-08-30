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
  AsyncStorage,
  ActivityIndicator
} from 'react-native';
import { NavigationActions, StackActions } from 'react-navigation';
//import LinearGradient from 'react-native-linear-gradient';

//import {} from 'react-navigation';

import {Icon, Container, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form } from 'native-base';

import Toast, {DURATION} from 'react-native-easy-toast'

const resetAction = StackActions.reset({
  index: 0,
  actions: [NavigationActions.navigate({ routeName: 'Home' })],
});
const resetAction2 = StackActions.reset({
  index: 0,
  actions: [NavigationActions.navigate({ routeName: 'Admingraph' })],
});
 
  export default class Login extends Component {
    constructor(props) {
      super(props);
      this.state = {
        animate1:false,
        dataSource:[],
        animating: false,
        email:"",
        password:"",
        devicetype:'',
        error: {},
        animate:false,
         selectedDate: "",
        isDateTimePickerVisible: false,
        animate2:false,
        isLoading: false,
        isSignInButtonEnable:false,
      };
    }
    goback() {
     
      this.props.navigation.goBack();
    }
    forgotpass()
    {
      this.props.navigation.navigate('Forgotpassword')
    }
    handleSubmit() {

     console.log(this.state.email);
     console.log(this.state.password);
     this.setState({
      animating: true
    })
     let formdata = new FormData();

formdata.append("email",this.state.email)
formdata.append("password", this.state.password)
formdata.append("signup_type", 'APP')
//formdata.append("login_type",this.props.navigation.getParam('login_type'))

formdata.append("device_token", '23E1CBEF7AB5DD39FA0029FAB64E7FE6D2CD6A532EC1FA78096A8D3F27A94FDA')
formdata.append("device_id", 'E7E5C2B6-C411-45B5-8D1F-383B59FD88E9')
formdata.append("device_type", 'ANDROID')
console.log(formdata)

//formdata.append("product[images_attributes[0][file]]", {uri: photo.uri, name: 'image.jpg', type: 'image/jpeg'})
 if(this.props.navigation.getParam('login_type')=='ADMIN')
{
fetch('http://idcaresteward.com/api/v1/user/login_admin',{
  method: 'post',
  headers: {
    'Content-Type': 'multipart/form-data',
  },
  body: formdata
  })
  .then((response) => response.json())
  .then((responseJson) => {
console.log(responseJson)

if(responseJson.status==1)
{
  this.setState({
    animating: false
  })
  AsyncStorage.setItem('userdata', JSON.stringify(responseJson.response));
 // this.props.navigation.navigate('Home'); 
if(responseJson.response.login_role=='Md Steward')
{

  this.props.navigation.dispatch(resetAction);
}
else if(responseJson.response.login_role=='Data Operator')
{

  this.props.navigation.dispatch(resetAction);
}
else if(responseJson.response.login_role=='Admin')
{
  this.props.navigation.dispatch(resetAction2);
}


}
else{
 // this.props.navigation.navigate('Home'); 
  //alert(responseJson.message)
  this.setState({
    animating: false
  })
  this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
  <Text style={{color:'white'}}>
 {responseJson.message}

  </Text>
  </View>); 
}
    //return responseJson.movies;
  })
  .catch(err => {
    console.log(err)
  })  

}

else if(this.props.navigation.getParam('login_type')=='MDSTEWARD')
{
fetch('http://idcaresteward.com/api/v1/user/login_md_steward',{
  method: 'post',
  headers: {
    'Content-Type': 'multipart/form-data',
  },
  body: formdata
  })
  .then((response) => response.json())
  .then((responseJson) => {
console.log(responseJson)

//this.props.navigation.navigate('PatientcurrentDetail',{patdetail:''}); 

if(responseJson.status==1)
{
  this.setState({
    animating: false
  })
  AsyncStorage.setItem('userdata', JSON.stringify(responseJson.response));
 // this.props.navigation.navigate('Home'); 
if(responseJson.response.login_role=='Md Steward')
{

  this.props.navigation.dispatch(resetAction);
}
else if(responseJson.response.login_role=='Data Operator')
{

  this.props.navigation.dispatch(resetAction);
}
else if(responseJson.response.login_role=='Admin')
{
alert('Comminf soon.')
}


}
else{
 // this.props.navigation.navigate('Home'); 
  //alert(responseJson.message)
  this.setState({
    animating: false
  })
  this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
  <Text style={{color:'white'}}>
 {responseJson.message}

  </Text>
  </View>); 
}
    //return responseJson.movies;
  })
  .catch(err => {
    console.log(err)
  })  

}



else if(this.props.navigation.getParam('login_type')=='DATAOPERATOR')
{
fetch('http://idcaresteward.com/api/v1/user/login_data_operator',{
  method: 'post',
  headers: {
    'Content-Type': 'multipart/form-data',
  },
  body: formdata
  })
  .then((response) => response.json())
  .then((responseJson) => {
console.log(responseJson)

if(responseJson.status==1)
{
  this.setState({
    animating: false
  })
  AsyncStorage.setItem('userdata', JSON.stringify(responseJson.response));
 // this.props.navigation.navigate('Home'); 
if(responseJson.response.login_role=='Md Steward')
{

  this.props.navigation.dispatch(resetAction);
}
else if(responseJson.response.login_role=='Data Operator')
{

  this.props.navigation.dispatch(resetAction);
}
else if(responseJson.response.login_role=='Admin')
{
alert('Comminf soon.')


}


}
else{
 // this.props.navigation.navigate('Home'); 
  //alert(responseJson.message)
  this.setState({
    animating: false
  })
  this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
  <Text style={{color:'white'}}>
 {responseJson.message}

  </Text>
  </View>); 
}
    //return responseJson.movies;
  })
  .catch(err => {
    console.log(err)
  })  

}



    }
  
    render() {
  
  return (
  
    <Container>

{this.state.animating==true &&
            <ActivityIndicator
               animating = {this.state.animating}
               color = '#B22B57'
               size = "large"
               style = {styles.activityIndicator}/>
     }


<Toast style={{backgroundColor:'black'}} ref="toast" fadeOutDuration={3000}/>  

          <View style={{ flex:1,  flexDirection:'column',justifyContent:'space-around'}}>
          <View style={{ height:'40%', justifyContent:'center',padding:5,alignContent:'center',alignItems:'center'}}>
         
         <Image style={{marginTop:40, resizeMode:'stretch',width:200,height:80}}  source={require('./image/logo.png')} ></Image>
        </View>
          <ScrollView>
         
                

          <View style={{
            
            flex:1,width:'100%',height:'100%',alignSelf:'center',padding:19}}>
            
          <Text style={{fontSize:12,padding:4,color:'#933A63',fontWeight:'bold'}}>Login To Continue</Text>
          <View style={{height:40, margin:3,marginBottom:6, backgroundColor:'#F7F7F7', borderLeftColor:'#AC2C57',borderLeftWidth:5,borderRadius:5}}>
           <Item style={{paddingLeft:6}}>
            <Icon  name='home' />
            <TextInput 
      style={{ height:40, width:'100%'}}
          onChangeText={email => this.setState({ email })}
          onSubmitEditing={() => this.password.focus()}
          placeholder='Email' />
             </Item>
            </View>
          {/* <View >
            <TextInput 

            
            // onChangeText={FirstName => this.setState({ FirstName })}
             onSubmitEditing={() => this.password.focus()}
             placeholder='Username' />
          </View> */}
         <View style={{  height:40,margin:3,marginBottom:6,backgroundColor:'#F7F7F7',margin:3,borderLeftColor:'#AC2C57',borderLeftWidth:5,borderRadius:4}}>
         <Item style={{paddingLeft:7}}>
            <Icon color='#f50' name='lock' type="FontAwesome" /> 
            {/* <Image style={{width:30,height:30}}  source={require('./image/password.png')} ></Image> */}
            <TextInput  
            style={{ height:40,width:'100%'}}
            secureTextEntry={true} 
            ref={input => (this.password = input)}
            onChangeText={password => this.setState({ password })}
            placeholder='Password' />
          </Item>
            </View>
            
            <TouchableOpacity  onPress={this.forgotpass.bind(this)} style={{flexDirection:'row', padding:10,width:'100%',justifyContent:'flex-end'}}><Text style={{justifyContent:'flex-end',fontSize:12,color:'#933A63',textDecorationLine:'underline'}}>Forgot Password?</Text></TouchableOpacity>
             <View style={{flexDirection:'row',paddingBottom:15}}><Text  style={{justifyContent:'flex-end',fontSize:12,color:'#525252'}}>You are trying to login as {this.props.navigation.getParam('rolename')}. </Text><TouchableOpacity onPress={this.goback.bind(this)}><Text  style={{fontSize:12,color:'#933A63',textDecorationLine:'underline'}}>Click here to change</Text></TouchableOpacity></View>
             <TouchableOpacity style={{borderRadius:7, backgroundColor:'#8F3A5D',height:45,alignItems:'center',justifyContent:'center'}} onPress={this.handleSubmit.bind(this)} >
                
                    <Text style={{color:'white',fontSize:18}}> Login </Text>
                 
                </TouchableOpacity> 

                
          </View>
          
          {/* <View><Text style={{textAlign:'center'}}>Version 1.2</Text></View> */}
          </ScrollView>     
           </View>
           
         


          
  </Container>


  );
}
};


var styles = StyleSheet.create({
  linearGradient: {
    flex: 1,
    paddingLeft: 15,
    paddingRight: 15,
    borderRadius: 5
  },
  buttonText: {
    fontSize: 18,
    fontFamily: 'Gill Sans',
    textAlign: 'center',
    margin: 10,
    color: '#ffffff',
    backgroundColor: 'transparent',
  },
  activityIndicator: {
    //  flex: 1,
    position: 'absolute',
    left: 0,
    right: 0,
    top: 0,
    bottom: 0,
    alignItems: 'center',
    justifyContent: 'center',
    //backgroundColor:'#F5FCFF',
 //   backgroundColor: 'rgba(40, 40, 40, 0.5)',
    zIndex:9999
   }
});